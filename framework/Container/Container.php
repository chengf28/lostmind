<?php

namespace Core\Container;

/**
 * 容器类
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class Container implements \ArrayAccess
{
    /**
     * 绑定的类容器
     * @var array
     * IF I CAN GO DEATH, I WILL
     */
    protected $binds     = [];

    /**
     * 存放已经实例化好的类容器
     * @var array
     * IF I CAN GO DEATH, I WILL
     */
    protected $instances = [];

    /**
     * 参数
     * @var array
     * IF I CAN GO DEATH, I WILL
     */
    protected $withs     = [];

    /**
     * 通过抽象名绑定一个具体的参数
     * @param string $abstract
     * @param mixed $concrete
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    public function bind(string $abstract, $concrete)
    {
        $single = false;
        $this->binds[$abstract] = compact('concrete', 'single');
    }

    /**
     * 初始化后一直保存
     * @param string $abstract
     * @param mixed $concrete
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    public function singleBind(string $abstract, $concrete)
    {
        $single = true;
        $this->binds[$abstract] = compact('concrete', 'single');
    }

    /**
     * 直接存入已经实例化号的类
     * @param string $abstract
     * @param mixed $concrete
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    public function instances(string $abstract, $concrete)
    {
        $this->instances[$abstract] = $concrete;
    }

    /**
     * 实例化一个类
     * @param  mixed $abstract
     * @return mixed
     * IF I CAN GO DEATH, I WILL
     */
    public function make($abstract)
    {
        return $this->makeWith($abstract);
    }

    /**
     * 带上参数实例化一个类型
     * @param mixed $abstract
     * @param array $parameters
     * @return mixed
     * IF I CAN GO DEATH, I WILL
     */
    public function makeWith($abstract, array $parameters = [])
    {
        // 入栈
        if (!empty($parameters)) {
            $this->withs[] = $parameters;
        }
        /**
         * 从短名中获取到对应真正的类名
         */
        $concrete = $this->getConcrete($abstract);

        /**
         * 查看是否已经在实例化的
         */
        if ($this->isInstance($abstract)) {
            return $this->getInstance($abstract);
        }
        if ($this->isBuildable($abstract, $concrete)) {
            // 满足构建条件,构建(实例化)该类
            $object = $this->build($concrete);
        } else {
            $object = $this->makeWith($concrete);
        }

        /**
         * 如果有参数,则将参数出栈
         */
        if (count($this->withs)) {
            array_pop($this->withs);
        }

        /**
         * 如果是singleBind 则第一次实例化后存instances中
         */
        if ($this->isSingle($abstract)) {
            $this->instances[$concrete] = $object;
        }

        // 返回实例化好的对象
        return $object;
    }

    /**
     * 获取到已经绑定的类的具体
     * @param string $abstract
     * @return mixed
     * IF I CAN GO DEATH, I WILL
     */
    protected function isBind(string $abstract)
    {
        return isset($this->binds[$abstract]);
    }

    /**
     * 获取到对应的实例
     * @param string $abstract
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    protected function getConcrete(string $abstract)
    {
        return $this->isBind($abstract) ? $this->binds[$abstract]['concrete'] : $abstract;
    }

    /**
     * 是否分享模式
     * @param string $abstract
     * @return bool
     * IF I CAN GO DEATH, I WILL
     */
    protected function isSingle(string $abstract)
    {
        return $this->isBind($abstract) ? $this->binds[$abstract]['single'] : false;
    }

    /**
     * 是否存在在单例容器中
     * @param string $abstract
     * @return bool
     * IF I CAN GO DEATH, I WILL
     */
    protected function isInstance(string $abstract)
    {
        return isset($this->instances[$abstract]);
    }

    /**
     * 获取到单例
     * @param string $abstract
     * @return mixed
     * IF I CAN GO DEATH, I WILL
     */
    protected function getInstance(string $abstract)
    {
        return $this->instances[$abstract];
    }
    /**
     * 判断一个concrete是否可以去build 
     * @param mixed $abstract
     * @param mixed $concrete
     * @return bool
     * IF I CAN GO DEATH, I WILL
     */
    protected function isBuildable($abstract, $concrete)
    {
        // 满足 实例和抽象同名(类的具体名称) 或者 实例为一个匿名方法则认为可以去构建
        return ($concrete === $abstract || $this->isClosure($concrete));
    }

    /**
     * 判断是否属于Closure
     * @param mixed $concrete
     * @return bool
     * IF I CAN GO DEATH, I WILL
     */
    private function isClosure($concrete)
    {
        return $concrete instanceof \Closure;
    }

    /**
     * 构建函数
     * @param mixed $concrete
     * @param array $parameter
     * @return mixed
     * IF I CAN GO DEATH, I WILL
     */
    protected function build($concrete, array $parameter = [])
    {
        // 如果是构造函数
        if ($this->isClosure($concrete)) {
            return call_user_func($concrete, $parameter);
        }
        // 反射
        $reflectionclass = new \ReflectionClass('\\' . $concrete);

        // 判断是否可以实例化
        if (!$reflectionclass->IsInstantiable()) {
            throw new Exception("The class {$concrete} can't instantiable , please check it ", 1);
        }
        $construct = $reflectionclass->getConstructor();
        // 如果返回NULL则不存在
        if (!$construct) {
            // 直接实例化该类返回
            return new $concrete;
        }
        // 查看实例化函数是否有参数需要注入
        $buildNeeds = $this->buildParameters($construct->getParameters());
        // 实例化
        return $reflectionclass->newInstanceArgs($buildNeeds);
    }

    protected function buildParameters(array $parameters)
    {
        $return = [];
        foreach ($parameters as $parameter) {
            // 如果有参数,则判断是否在参数数组中
            if (!empty($withs = $this->getFinalParameter()) /* && isset($withs[$parameter->getName()]) */) {
                $return[] = $withs;
                continue;
            }

            // 如果不在参数堆中,检查是否为一个类,然后实例化他
            if (!is_null($class = $parameter->getClass())) {
                $return[] = $this->make($class->name);
            }
        }
        return $return;
    }

    /**
     * 获取到最近的参数;
     * @return void
     * IF I CAN GO DEATH, I WILL
     */
    protected function getFinalParameter()
    {
        return count($this->withs) ? end($this->withs) : [];
    }

    public function offsetGet($offset)
    {
        return $this->make($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->bind(
            $offset,
            $value instanceof \Closure ? $value : function () use ($value) {
                return $value;
            }
        );
    }

    public function offsetUnset($offset)
    {
        unset($this->binds[$offset], $this->instances[$offset]);
    }

    public function offsetExists($offset)
    {
        return $this->isBind($offset) && $this->isInstance($offset);
    }
}
