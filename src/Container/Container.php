<?php

namespace PHPDIContainer\Container;

use PHPDIContainer\Container\Exceptions\ContainerException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $services = [];

    public function add(string $id, string|object $concrete = null): void
    {
        if (is_null($concrete)) {
            // Если $id не является классом
            if (! class_exists($id)) {
                throw new ContainerException("Service $id not found");
            }
            $concrete = $id;
        }
        $this->services[$id] = $concrete;
    }

    public function get(string $id): object
    {
        if (! $this->has($id)) {
            // Если $id не является классом
            if (! class_exists($id)) {
                throw new ContainerException("Service $id could not be found");
            }

            $this->add($id);
        }

        $instance = $this->resolve($this->services[$id]);

        return $instance;
    }

    private function resolve($class)
    {
        // 1. Создает экземпляр класса Reflection
        $reflectionClass  = new \ReflectionClass($class);

        // 2. Использует Reflection для попытки получить конструктор
        // Если конструктора нет, то метод getConstructor() вернет false
        $constructor = $reflectionClass->getConstructor();

        // 3. Если конструктора нет, просто создает экземпляр
        if (is_null($constructor))
        {
            return $reflectionClass->newInstance();
        }

        // 4. Получает параметры конструктора
        $constructorParams = $constructor->getParameters();

        // 5. Получает зависимости
        $classDependencies = $this->resolveClassDependencies($constructorParams);

        // 6. Создает экземпляр с зависимостями
        $instance = $reflectionClass->newInstanceArgs($classDependencies);

        // 7. Возвращает объект
        return $instance;
    }

    private function resolveClassDependencies(array $constructorParams): array
    {
        // 1. Инициализирует пустой список зависимостей
        $classDependencies = [];

        // 2. Попытается найти и создать экземпляр
        /** @var \ReflectionParameter $constructorParam */
        foreach ($constructorParams as $constructorParam) {
            // Получает параметры
            $serviceType = $constructorParam->getType();

            // Попытается создать экземпляр
            $service = $this->get($serviceType->getName());

            // Добавляет сервис в classDependencies
            $classDependencies[] = $service;
        }

        // 3. Возвращает массив
        return $classDependencies;
    }

    public function has(string $id): bool
    {
        // Можно было использовать isset
        // return isset($this->service[$id]
        return array_key_exists($id, $this->services);
    }
}
