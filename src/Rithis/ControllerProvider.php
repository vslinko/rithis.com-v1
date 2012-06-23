<?php

namespace Rithis;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function () use ($app) {
            return $app->redirect('/about');
        });

        $controllers->get('/about', function () use ($app) {
            return $app['twig']->render('who-we-are.twig');
        })->bind('who_we_are');

        $controllers->match('/services', function (Request $request) use ($app) {
            $form = $app['form.factory']->createBuilder('form')
                ->add('name', 'text', array(
                    'constraints' => new Assert\NotBlank(array(
                        'message' => 'We need to know your name',
                    )),
                ))
                ->add('company', 'text')
                ->add('email', 'text', array(
                    'constraints' => array(new Assert\NotBlank(array(
                        'message' => 'No mail, we will tell you nothing',
                    )), new Assert\Email(array(
                        'message' => 'Invalid e-mail',
                    ))),
                ))
                ->add('message', 'textarea', array(
                    'attr' => array('rows' => 6, 'class' => 'input-xlarge'),
                    'constraints' => array(new Assert\NotBlank(array(
                        'message' => 'Describe what are you need'
                    )), new Assert\MinLength(array(
                        'limit' => 10,
                        'message' => 'We need more information',
                    ))),
                ))
                ->getForm();

            if ('POST' == $request->getMethod()) {
                $form->bindRequest($request);

                if ($form->isValid()) {
                    $data = $form->getData();

                    $msg = '';
                    foreach (array('Name', 'Email', 'Company', 'Message') as $field) {
                        $msg .= $app['translator']->trans($field) . ":\n";
                        $msg .= $data[strtolower($field)] . "\n\n";
                    }

                    $message = \Swift_Message::newInstance()
                        ->setSubject($app['translator']->trans('Order'))
                        ->addFrom($data['email'], $data['name'] . ' (' . $data['company'] . ')')
                        ->addTo('manager@rithis.com')
                        ->setBody($msg);

                    $app['session']->start();

                    if ($app['mailer']->send($message) == 1) {
                        $app['session']->setFlash('success', 'Your order successfully sent');
                    } else {
                        $app['session']->setFlash('error', 'Sorry, unknown problem with your order, please use phone or e-mail');
                    }

                    return $app->redirect($app['url_generator']->generate('what_we_offer'));
                }
            }

            return $app['twig']->render('what-we-offer.twig', array(
                'form' => $form->createView(),
            ));
        })->bind('what_we_offer');

        $controllers->get('/tags', function () use ($app) {
            $tags = array(
                'Backend Development' => array(
                    'PHP 5.3+' => 'http://php.net',
                    'Symfony2' => 'http://symfony.com',
                    'Doctrine ORM/ODM/DBAL' => 'http://www.doctrine-project.org',
                    'Silex' => 'http://silex.sensiolabs.org',
                    'Twig' => 'http://twig.sensiolabs.org',
                    'Composer' => 'http://getcomposer.org',
                    'PHPUnit' => 'https://github.com/sebastianbergmann/phpunit',
                    'JavaScript' => 'http://www.ecmascript.org',
                    'CoffeeScript' => 'http://coffeescript.org',
                    'Git' => 'http://git-scm.com',
                ),
                'Frontend Development' => array(
                    'jQuery' => 'http://jquery.com',
                    'LESS' => 'http://lesscss.org',
                    'Bootstrap' => 'http://twitter.github.com/bootstrap',
                    'HTML5' => 'http://www.w3.org/TR/html5/',
                    'CSS3' => 'http://www.w3.org/Style/CSS/',
                    'Responsive Web Design' => 'http://alistapart.com/articles/responsive-web-design'
                ),
                'Server Management' => array(
                    'NGINX' => 'http://nginx.com',
                    'Ubuntu' => 'http://www.ubuntu.com',
                    'KVM' => 'http://www.linux-kvm.org/page/Main_Page',
                    'AWS' => 'http://aws.amazon.com',
                ),
                'Databases' => array(
                    'MySQL' => 'http://www.mysql.com',
                    'MongoDB' => 'http://www.mongodb.org',
                    'Memcached' => 'http://memcached.org',
                ),
                'Continuous Integration and Deployment' => array(
                    'Travis CI' => 'http://travis-ci.org',
                    'capifony' => 'http://capifony.org',
                    'Phing' => 'http://www.phing.info/trac/',
                ),
                'Project Management' => array(
                    'Basecamp' => 'http://basecamp.com',
                    'Kayako Fusion' => 'http://www.kayako.com/products/fusion',
                    'GitHub' => 'http://github.com',
                ),
                'Certifications' => array(
                    'Zend Certified Engineer PHP 5.3' => 'http://www.zend.com/en/yellow-pages#show-ClientCandidateID=ZEND017112',
                ),
            );

            return $app['twig']->render('our-tags.twig', array('tags' => $tags));
        })->bind('our_tags');

        return $controllers;
    }
}
