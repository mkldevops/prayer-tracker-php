<?php

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\MakerBundle\MakerBundle;
use SymfonyCasts\Bundle\ResetPassword\SymfonyCastsResetPasswordBundle;
use Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use FOS\JsRoutingBundle\FOSJsRoutingBundle;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use EasyCorp\Bundle\EasyAdminBundle\EasyAdminBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
return [
    FrameworkBundle::class => ['all' => true],
    DoctrineBundle::class => ['all' => true],
    DoctrineMigrationsBundle::class => ['all' => true],
    TwigBundle::class => ['all' => true],
    MakerBundle::class => ['dev' => true],
    SymfonyCastsResetPasswordBundle::class => ['all' => true],
    StofDoctrineExtensionsBundle::class => ['all' => true],
    WebProfilerBundle::class => ['dev' => true, 'test' => true],
    SensioFrameworkExtraBundle::class => ['all' => true],
    DoctrineFixturesBundle::class => ['dev' => true, 'test' => true],
    FOSJsRoutingBundle::class => ['all' => true],
    SwiftmailerBundle::class => ['all' => true],
    SecurityBundle::class => ['all' => true],
    EasyAdminBundle::class => ['all' => true],
    MonologBundle::class => ['all' => true],
];
