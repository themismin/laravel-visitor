<?php

namespace ThemisMin\LaravelVisitor;

use App\Paths\MiniProgramPath;
use Carbon\Carbon as c;
use Countable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use ThemisMin\LaravelVisitor\Services\Cache\CacheInterface;
use ThemisMin\LaravelVisitor\Services\Geo\GeoInterface;
use ThemisMin\LaravelVisitor\Storage\VisitorInterface;

/**
 * Class Visitor.
 */
class Visitor implements Countable
{
    /**
     * The Config array.
     *
     * @var string
     */
    protected $tableName = null;

    /**
     * The Option Repository Interface Instance.
     *
     * @var OpenInterface
     */
    protected $storage;

    /**
     * The Cache Interface.
     *
     * @var ThemisMin\LaravelVisitor\Services\Cache\CacheClass
     */
    protected $cache;

    /**
     * The Config Instance.
     *
     * @var Config
     */
    protected $collection;

    /**
     * @var Ip
     */
    protected $ip;

    /**
     * The Geo Interface.
     */
    protected $geo;

    /**
     * @var Model
     */
    protected $visitorRegistry;

    /**
     * @param VisitorInterface $storage
     * @param GeoInterface $geo
     * @param Ip $ip
     * @param CacheInterface $cache
     */
    public function __construct(
        VisitorInterface $storage,
        GeoInterface $geo,
        Ip $ip,
        CacheInterface $cache
    )
    {
        $this->storage = $storage;
        $this->geo = $geo;
        $this->ip = $ip;
        $this->cache = $cache;

        $this->collection = new Collection();

        $this->visitorRegistry = app(config('visitor.model'));
    }

    /**
     * @param null $ip
     *
     * @return null
     */
    public function get($ip = null)
    {
        if (!isset($ip)) {
            $ip = $this->ip->get();
        }

        if ($this->ip->isValid($ip)) {
            return $this->storage->get($ip);
        }
    }

    /**
     * @param $page
     * @param $options
     * @param $scene
     * @param $fuid
     */
    public function log($path, $options, $scene, $fuid)
    {
        $ip = $this->ip->get();
        if (!$this->ip->isValid($ip)) {
            return;
        }
        $geo = $this->geo->locate($ip);
        $country = array_key_exists('country_code', $geo) ? $geo['country_code'] : null;
        $city = array_key_exists('city', $geo) ? $geo['city'] : null;

        $user_id = auth()->id();
        $page = Paginator::resolveCurrentPage();

        $hit_type = MiniProgramPath::getModelNameByPath($path);
        $hit_id = null;
        if ($hit_type) {
            $hit_id = isset($options['id']) ? $options['id'] : null;
        }

        // ip doesnt exist  in db
        $data = [
            'ip' => $ip,
            'country' => $country,
            'city' => $city,

            'user_id' => $user_id,

            'scene' => $scene,
            'fuid' => $fuid,

            'path' => $path,
            'options' => $options,
            'page' => $page,

            'hittable_type' => $hit_type,
            'hittable_id' => $hit_id,

            'updated_at' => c::now(),
            'created_at' => c::now(),
        ];

        $model = config('visitor.model');
        (new $model)->create($data);

        //
        // $this->storage->create($data);
        //
        // // Clear the database cache
        // $this->cache->destroy('weboap.visitor');

    }

    public function hasHit($user_id, $hit_id, $hit_type, $ip)
    {
        return $this->visitorRegistry->where('ip', $ip)
            ->where('user_id', $user_id)
            ->where('hittable_type', $hit_type)
            ->where('hittable_id', $hit_id)->count() ? true : false;
    }

    /**
     * @param $ip
     */
    public function forget($ip)
    {
        if (!$this->ip->isValid($ip)) {
            return;
        }

        //delete the ip from db
        $this->storage->delete($ip);

        // Clear the database cache
        $this->cache->destroy('weboap.visitor');
    }

    /**
     * @param $ip
     *
     * @return bool
     */
    public function has($ip)
    {
        if (!$this->ip->isValid($ip)) {
            return false;
        }

        return $this->count($ip) > 0;
    }

    /**
     * @param null $ip
     *
     * @return mixed
     */
    public function count($ip = null)
    {
        //if ip null then return count of all visits
        return $this->storage->count($ip);
    }

    /**
     * @return mixed
     */
    public function all($collection = false)
    {
        $result = $this->cache->rememberForever('weboap.visitor', $this->storage->all());

        if ($collection) {
            return $this->collection->make($result);
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function clicks()
    {
        return $this->storage->clicksSum();
    }

    /**
     * @param $start
     * @param $end
     *
     * @return mixed
     */
    public function range($start, $end)
    {
        $start = date('Y-m-d H:i:s', strtotime($start));
        $end = date('Y-m-d 23:59:59', strtotime($end));

        return $this->storage->range($start, $end);
    }

    /**
     * clear database records / cached results.
     *
     * @return void
     */
    public function clear()
    {
        //clear database
        $this->storage->clear();

        // clear cached options
        $this->cache->destroy('weboap.visitor');
    }
}
