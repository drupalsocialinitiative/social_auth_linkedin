<?php

namespace Drupal\social_auth_linkedin;

use Drupal\social_auth\AuthManager\OAuth2Manager;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Manages the authentication requests.
 */
class LinkedinAuthManager extends OAuth2Manager {

  /**
   * The request object.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * The Linkedin service client.
   *
   * @var \LinkedIn\LinkedIn
   */
  protected $client;

  /**
   * Code returned by Linkedin for authentication.
   *
   * @var string
   */
  protected $code;

  /**
   * LinkedinLoginManager constructor.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request
   *   Used to get the parameter code returned by Linkedin.
   */
  public function __construct(RequestStack $request) {
    $this->request = $request->getCurrentRequest();
  }

  /**
   * {@inheritdoc}
   */
  public function authenticate() {
    $this->client->setAccessToken($this->getAccessToken());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAccessToken() {
    if (!$this->accessToken) {
      $this->accessToken = $this->client->getAccessToken($this->getCode());
    }

    return $this->accessToken;
  }

  /**
   * {@inheritdoc}
   */
  public function getUserInfo() {
    return $this->client->get('/people/~:(id,first-name,last-name,email-address,picture-urls::(original))');
  }

  /**
   * Gets the code returned by Linkedin to authenticate.
   *
   * @return string
   *   The code string returned by Linkedin.
   */
  protected function getCode() {
    if (!$this->code) {
      $this->code = $this->request->query->get('code');
    }

    return $this->code;
  }

}
