<?php

namespace ThemisMin\LaravelVisitor\Storage;

use Illuminate\Config\Repository as Config;
use Illuminate\Database\DatabaseManager as DB;

/**
 * Class QbVisitorRepository.
 */
class QbVisitorRepository implements VisitorInterface
{
    protected $model = null;

    /**
     *  Illuminate\Database\DatabaseManager Instance.
     *
     * @var Illuminate\Database\DatabaseManager
     **/
    protected $db;

    /**
     *  Config Instance.
     *
     * @var Illuminate\Config\Repository
     **/
    protected $config;

    public function __construct(Config $config, DB $db)
    {
        $this->config = $config;
        $this->db = $db;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return isset($this->model) ? $this->model : $this->config->get('visitor.model');
    }

    public function create(array $data)
    {
        return app($this->getModel())->insert($data);
    }

    public function get($ip)
    {
        return app($this->getModel())->whereIp($ip)->first();
    }

    public function update($ip, array $data)
    {
        return app($this->getModel())->whereIp($ip)->update($data);
    }

    public function delete($ip)
    {
        return app($this->getModel())->whereIp($ip)->delete();
    }

    public function all()
    {
        return app($this->getModel())->get();
    }

    public function count($ip = null)
    {
        if (!isset($ip)) {
            return app($this->getModel())->count();
        } else {
            return app($this->getModel())->whereIp($ip)->count();
        }
    }

    public function increment($ip)
    {
        app($this->getModel())->whereIp($ip)->increment('clicks');
    }

    public function clicksSum()
    {
        return app($this->getModel())->sum('clicks');
    }

    public function range($start, $end)
    {
        return app($this->getModel())->whereBetween('created_at', [$start, $end])->count();
    }

    /**
     * delete all options from db.
     *
     * @return void
     */
    public function clear()
    {
        app($this->getModel())->truncate();
    }
}
