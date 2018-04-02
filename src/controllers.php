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

    $message = new Swift_Message();
    $message
        ->setSubject('[PLOPLEO] new message')
        ->setFrom(array('noreply@plopleo.com'))
        ->setTo(array('leopold.pelissier@gmail.com'))
        ->setBody($content);

    $app['mailer']->send($message);

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
