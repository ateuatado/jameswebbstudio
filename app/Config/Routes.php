<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('sitemap.xml', 'Sitemap::index');

service('auth')->routes($routes);

// ─── Admin Routes ─────────────────────────────────────────────────────────────
$routes->group('admin', ['filter' => 'group:admin,superadmin'], static function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');

    // Photos (heróis)
    $routes->get( 'heroes/(:num)/photos',              'Admin\HeroController::photos/$1');
    $routes->post('heroes/(:num)/photos',              'Admin\HeroController::uploadPhoto/$1');
    $routes->post('heroes/(:num)/photos/order',        'Admin\HeroController::updatePhotoOrder/$1');
    $routes->post('heroes/photos/(:num)/delete',       'Admin\HeroController::deletePhoto/$1');
    $routes->post('heroes/photos/(:num)/update',       'Admin\HeroController::updatePhoto/$1');
    $routes->post('heroes/(:num)/photos/(:num)/cover', 'Admin\HeroController::setCover/$1/$2');

    // Publicação
    $routes->post('heroes/(:num)/publish',   'Admin\HeroController::publish/$1');
    $routes->post('heroes/(:num)/unpublish', 'Admin\HeroController::unpublish/$1');

    // CTA & Landing Page Blocks
    $routes->get( 'heroes/(:num)/cta',                      'Admin\HeroController::cta/$1');
    $routes->post('heroes/(:num)/cta',                      'Admin\HeroController::updateCta/$1');
    $routes->post('heroes/(:num)/cta/blocks',               'Admin\HeroController::ctaBlockCreate/$1');
    $routes->post('heroes/(:num)/cta/blocks/(:num)',         'Admin\HeroController::ctaBlockUpdate/$1/$2');
    $routes->post('heroes/(:num)/cta/blocks/(:num)/delete',  'Admin\HeroController::ctaBlockDelete/$1/$2');
    $routes->post('heroes/(:num)/cta/blocks/order',          'Admin\HeroController::ctaBlocksOrder/$1');

    // Agendamento (por herói)
    $routes->get( 'heroes/(:num)/schedule',       'Admin\ScheduleController::index/$1');
    $routes->post('heroes/(:num)/schedule',       'Admin\ScheduleController::store/$1');
    $routes->post('heroes/(:num)/schedule/bulk',  'Admin\ScheduleController::bulk/$1');
    $routes->post('schedules/(:num)/delete',      'Admin\ScheduleController::delete/$1');

    // Intenções
    $routes->get( 'intentions',              'Admin\IntentionController::index');
    $routes->post('intentions/(:num)/delete', 'Admin\IntentionController::delete/$1');

    // Bookings globais
    $routes->get( 'bookings',              'Admin\BookingController::index');
    $routes->post('bookings/(:num)/delete', 'Admin\BookingController::delete/$1');

    // Pacotes, Categorias & Serviços
    $routes->resource('packages', ['controller' => 'Admin\PackageController', 'websafe' => 1]);
    $routes->resource('categories', ['controller' => 'Admin\CategoryController', 'websafe' => 1]);
    $routes->resource('services', ['controller' => 'Admin\ServiceController', 'websafe' => 1]);

    // Pedidos (orders)
    $routes->get('orders',               'Admin\OrderController::index');
    $routes->get('orders/testar-email',  'Admin\OrderController::testEmail');
    $routes->get('orders/(:num)',        'Admin\OrderController::show/$1');
    $routes->post('orders/(:num)/contract', 'Admin\OrderController::updateContract/$1');
    $routes->get('orders/(:num)/contract',  'Admin\OrderController::generateContract/$1');

    // Projetos de clientes + sync S3
    $routes->get( 'client-projects/(:num)/photos',   'Admin\ClientProjectController::photos/$1');
    $routes->get( 'client-projects/(:num)/poll',     'Admin\ClientProjectController::pollInteractions/$1');
    $routes->post('client-projects/(:num)/sync-s3',  'Admin\ClientProjectController::syncS3/$1');
    $routes->get( 'client-projects/(:num)/download-bat', 'Admin\ClientProjectController::downloadBat/$1');
    $routes->resource('client-projects', ['controller' => 'Admin\ClientProjectController', 'websafe' => 1]);

    // Hero CRUD (resource por último para não sobrescrever rotas acima)
    $routes->resource('heroes', ['controller' => 'Admin\HeroController', 'websafe' => 1]);

    // Guia Pré-Ensaio
    $routes->get( 'guide-sections',              'Admin\GuideSectionController::index');
    $routes->get( 'guide-sections/create',       'Admin\GuideSectionController::create');
    $routes->post('guide-sections/store',        'Admin\GuideSectionController::store');
    $routes->get( 'guide-sections/preview',      'Admin\GuideSectionController::preview');
    $routes->get( 'guide-sections/(:num)/edit',  'Admin\GuideSectionController::edit/$1');
    $routes->post('guide-sections/(:num)/update','Admin\GuideSectionController::update/$1');
    $routes->post('guide-sections/(:num)/delete','Admin\GuideSectionController::delete/$1');

    // Contrato
    $routes->get( 'contract-sections',              'Admin\ContractSectionController::index');
    $routes->get( 'contract-sections/create',       'Admin\ContractSectionController::create');
    $routes->post('contract-sections/store',        'Admin\ContractSectionController::store');
    $routes->get( 'contract-sections/preview',      'Admin\ContractSectionController::preview');
    $routes->get( 'contract-sections/(:num)/edit',  'Admin\ContractSectionController::edit/$1');
    $routes->post('contract-sections/(:num)/update','Admin\ContractSectionController::update/$1');
    $routes->post('contract-sections/(:num)/delete','Admin\ContractSectionController::delete/$1');

    // Dados do Estúdio
    $routes->get( 'studio',        'Admin\StudioSettingController::index');
    $routes->post('studio/update', 'Admin\StudioSettingController::update');
});

// ─── Agenda Proxy (resolve CORS/SSL server-side) ──────────────────────────────
$routes->get( 'agenda-api/availability', 'AgendaProxy::availability');
$routes->post('agenda-api/book',         'AgendaProxy::book');

// ─── Intenções públicas ────────────────────────────────────────────────────────
$routes->post('intentions/store', 'IntentionController::store');

// ─── Agendamento público ───────────────────────────────────────────────────────
$routes->get( 'schedule/slots/(:num)', 'ScheduleController::getSlots/$1');
$routes->post('schedule/book',         'ScheduleController::book');

// ─── API Callback do Lambda (Auto-tagging) ──────────────────────────────────
$routes->post('api/photo/metadata', 'Api\ApiController::saveMetadata');

// ─── Página de Investimento (pública) ────────────────────────────────────────
$routes->get('investimento', 'Pricing::index');

// ─── Checkout de Pacotes (público) ───────────────────────────────────────────
$routes->post('comprar-ensaio',  'PackageCheckout::buy');
$routes->post('quero-falar',     'PackageCheckout::talkFirst');
$routes->post('mp/webhook',      'PackageCheckout::webhook');
$routes->get( 'ensaio/obrigado', 'PackageCheckout::thanks');
$routes->get( 'ensaio/falha',    'PackageCheckout::failure');
$routes->get( 'ensaio/pendente', 'PackageCheckout::pending');
$routes->get( 'ensaio/status/(:num)', 'PackageCheckout::orderStatus/$1');


// ─── Portal do Cliente (autenticado) ──────────────────────────────────────────
$routes->group('client', ['filter' => 'session'], static function ($routes) {
    $routes->get( 'meus-ensaios',                   'Client\MeusEnsaiosController::index');
    $routes->get( 'galeria',                       'Client\GaleriaController::index');
    $routes->get( 'galeria/(:num)',                'Client\GaleriaController::view/$1');
    $routes->get( 'galeria/(:num)/poll',           'Client\GaleriaController::pollPhotos/$1');
    $routes->post('galeria/(:num)/save',           'Client\GaleriaController::saveSelection/$1');
    $routes->post('galeria/(:num)/photo/(:num)/status', 'Client\GaleriaController::togglePhotoStatus/$1/$2');
    $routes->post('galeria/(:num)/photo/(:num)/love',   'Client\GaleriaController::togglePhotoLove/$1/$2');
    $routes->post('galeria/(:num)/photo/(:num)/rate',   'Client\GaleriaController::ratePhoto/$1/$2');
    $routes->get( 'galeria/(:num)/checkout',       'Client\GaleriaController::checkout/$1');
    $routes->get( 'guia-pre-ensaio/(:num)',         'Client\MeusEnsaiosController::downloadGuide/$1');
    $routes->get( 'contrato/(:num)',                'Client\MeusEnsaiosController::downloadContract/$1');
});

// ─── Landing page de copy — /{slug}/agendar ───────────────────────────────────
// Deve vir ANTES do catch-all de slug
$routes->get('(:segment)/agendar', 'LandingPage::view/$1');

// ─── Página pública do herói por slug (catch-all — deve ser a última) ─────────
$routes->get('(:segment)', 'HeroPage::view/$1', ['priority' => 99]);
