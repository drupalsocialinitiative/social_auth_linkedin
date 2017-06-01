<?php

namespace Drupal\Tests\social_auth_linkedin\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\social_auth_linkedin\LinkedinAuthManager;

/**
 * @coversDefaultClass Drupal\social_auth_linkedin\LinkedinAuthManager
 * @group social_auth_linkedin
 */
class LinkedinAuthManagerTest extends UnitTestCase {
  /**
   * Session object.
   *
   * @var \Symfony\Component\HttpFoundation\Session\Session
   */
  protected $session;

  /**
   * Request object.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * Google Client object.
   *
   * @var \Google_Client
   */
  protected $client;

  /**
   * Linkedin Authentication manager.
   *
   * @var \Drupal\social_auth_linkedin\LinkedinAuthManager
   */
  protected $linkedinManager;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->request = $this->getMock('\Symfony\Component\HttpFoundation\RequestStack');

    $this->linkedinManager = new LinkedinAuthManager(
      $this->request
    );

    $this->client = $this->getMockBuilder('\LinkedIn\LinkedIn')
      ->disableOriginalConstructor()
      ->getMock();
  }

  /**
   * Tests set Google_Client object.
   *
   * @covers ::setClient
   */
  public function testSetLinkedinClient() {
    $this->assertInstanceOf('Drupal\social_auth_linkedin\LinkedinAuthManager',
      $this->setClient());
  }

  /**
   * Sets \LinkedIn\LinkedIn object to LinkedinAuthManager.
   *
   * @return \Drupal\social_auth_linkedin\LinkedinAuthManager
   *   setClient() returns $this, the LinkedinAuthManager object.
   */
  protected  function setClient() {
    return $this->linkedinManager->setClient($this->client);
  }

}
