<?php

namespace Drupal\social_auth_linkedin\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\social_auth_linkedin\LinkedinAuthManager;
use Drupal\social_api\Plugin\NetworkManager;
use Drupal\social_auth\SocialAuthUserManager;
use LinkedIn\LinkedIn;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Zend\Diactoros\Response\RedirectResponse;

/**
 * Manages requests to Linkedin API.
 */
class LinkedinAuthController extends ControllerBase {

  /**
   * Request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  public $request;

  /**
   * The network plugin manager.
   *
   * @var \Drupal\social_api\Plugin\NetworkManager
   */
  protected $networkManager;

  /**
   * The Linkedin authentication manager.
   *
   * @var \Drupal\social_auth_linkedin\LinkedinAuthManager
   */
  protected $linkedinManager;

  /**
   * The user manager.
   *
   * @var \Drupal\social_auth\SocialAuthUserManager
   */
  protected $userManager;

  /**
   * The session manager.
   *
   * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
   */
  protected $session;

  /**
   * LinkedinLoginController constructor.
   *
   * @param \Drupal\social_api\Plugin\NetworkManager $network_manager
   *   Used to get an instance of social_auth_linkedin network plugin.
   * @param \Drupal\social_auth\SocialAuthUserManager $user_manager
   *   Manages user login/registration.
   * @param \Drupal\social_auth_linkedin\LinkedinAuthManager $linkedin_manager
   *   Used to manage authentication methods.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request
   *   Used to access GET parameters.
   * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
   *   Used to store the access token into a session variable.
   */
  public function __construct(NetworkManager $network_manager, SocialAuthUserManager $user_manager, LinkedinAuthManager $linkedin_manager, RequestStack $request, SessionInterface $session) {
    $this->request = $request;
    $this->networkManager = $network_manager;
    $this->userManager = $user_manager;
    $this->linkedinManager = $linkedin_manager;
    $this->session = $session;

    // Sets the plugin id.
    $this->userManager->setPluginId('social_auth_linkedin');

    // Sets the session keys to nullify if user could not logged in.
    $this->userManager->setSessionKeysToNullify(['social_auth_linkedin_access_token']);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.network.manager'),
      $container->get('social_auth.user_manager'),
      $container->get('social_auth_linkedin.manager'),
      $container->get('request_stack'),
      $container->get('session')
    );
  }

  /**
   * Redirect to Linkedin Services Authentication page.
   *
   * @return \Zend\Diactoros\Response\RedirectResponse
   *   Redirection to Linkedin Accounts.
   */
  public function redirectToLinkedin() {
    /* @var \LinkedIn\LinkedIn $client */
    $client = $this->networkManager->createInstance('social_auth_linkedin')->getSdk();

    $url = $client->getLoginUrl([
      LinkedIn::SCOPE_BASIC_PROFILE,
      LinkedIn::SCOPE_EMAIL_ADDRESS,
    ]);

    return new RedirectResponse($url);
  }

  /**
   * Callback function to login user.
   */
  public function callback() {

    $error = $this->request->getCurrentRequest()->get('error');

    if ($error) {
      drupal_set_message($this->t('You could not be authenticated.'), 'error');
      return $this->redirect('user.login');
    }

    /* @var \Linkedin\LinkedIn $client */
    $client = $this->networkManager->createInstance('social_auth_linkedin')->getSdk();

    $this->linkedinManager->setClient($client)->authenticate();

    // Saves access token so that event subscribers can call Linkedin API.
    $this->session->set('social_auth_linkedin_access_token', $this->linkedinManager->getAccessToken());

    // Gets user information.
    $user = $this->linkedinManager->getUserInfo();

    // If user information could be retrieved.
    if ($user) {
      $picture = (isset($user['pictureUrls']['values'][0])) ? $user['pictureUrls']['values'][0] : FALSE;

      $fullname = $user['firstName'] . ' ' . $user['lastName'];
      return $this->userManager->authenticateUser($user['emailAddress'], $fullname, $user['id'], $picture);
    }

    drupal_set_message($this->t('You could not be authenticated, please contact the administrator'), 'error');
    return $this->redirect('user.login');
  }

}
