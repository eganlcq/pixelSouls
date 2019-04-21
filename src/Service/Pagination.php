<?php

namespace App\Service;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class Pagination {

    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $twig;
    private $route;
    private $templatePath;
    private $critera = [];
    private $orderBy = [];

    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request, $templatePath) {

        $this->manager = $manager;
        $this->twig = $twig;
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->templatePath = $templatePath;
    }

    public function getData() {

        $offset = ($this-> currentPage - 1) * $this->limit;
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy($this->critera, $this->orderBy, $this->limit, $offset);
        return $data;
    }

    public function getPages() {

        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findBy($this->critera));
        $pages = ceil($total / $this->limit);
        return $pages;
    }

    public function display($options = []) {

        $this->twig->display($this->templatePath, array_merge([
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ], $options));
    }

    public function getEntityClass() {

        return $this->entityClass;
    }

    public function setEntityClass($entityClass) {

        $this->entityClass = $entityClass;
        return $this;
    }

    public function getLimit() {

        return $this->limit;
    }

    public function setLimit($limit) {

        $this->limit = $limit;
        return $this;
    }

    public function getCurrentPage() {

        return $this->currentPage;
    }

    public function setCurrentPage($currentPage) {

        $this->currentPage = $currentPage;
        return $this;
    }

    public function getRoute() {

        return $this->route;
    }

    public function setRoute($route) {

        $this->route = $route;
        return $this;
    }

    public function getTemplatePath() {

        return $this->templatePath;
    }

    public function setTemplatePath($templatePath) {

        $this->templatePath = $templatePath;
        return $this;
    }

    public function getCritera() {

        return $this->critera;
    }

    public function setCritera($critera) {

        $this->critera = $critera;
        return $this;
    }

    public function getOrderBy() {

        return $this->orderBy;
    }

    public function setOrderBy($orderBy) {

        $this->orderBy = $orderBy;
        return $this;
    }
}