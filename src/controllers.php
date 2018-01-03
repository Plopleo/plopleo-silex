<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig', array());
})
    ->bind('homepage');

$app->post('/contact', function (Request $request, Silex\Application $app) {
    $name = $request->get('name');
    $email = $request->get('email');
    $message = $request->get('message');

    $content = 'Nom : ' . $app->escape($name) . ' || ';
    $content .= 'Email : ' . $app->escape($email) . ' || ';
    $content .= 'Message : ' . $app->escape($message);

    mail('leopold.pelissier@gmail.com', '[Plopleo] Contact', $content);

    return new Response('Merci pour votre message !', 201);
});

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $templates = array(
        'errors/error.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
