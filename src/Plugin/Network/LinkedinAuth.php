<?php

namespace Drupal\social_auth_linkedin\Plugin\Network;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Render\MetadataBubblingUrlGenerator;
use Drupal\social_api\SocialApiException;
use Drupal\social_auth\Plugin\Network\SocialAuthNetwork;
use LinkedIn\LinkedIn;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines Social Auth Linkedin Network Plugin.
 *
 * @Network(
 *   id = "social_auth_linkedin",
 *   social_network = "Linkedin",
 *   type = "social_auth",
 *   handlers = {
 *     "settings": {
 *       "class": "\Drupal\social_auth_linkedin\Settings\LinkedinAuthSettings",
 *       "config_id": "social_auth_linkedin.settings"
 *     }
 *   }
 * )
 */
class LinkedinAuth extends SocialAuthNetwork {
  /**
   * The url generator.
   *
   * @var \Drupal\Core\Render\MetadataBubblingUrlGenerator
   */
  protected $urlGenerator;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('url_generator'),
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('config.factory')
    );
  }

  /**
   * LinkedinLogin constructor.
   *
   * @param \Drupal\Core\Render\MetadataBubblingUrlGenerator $url_generator
   *   Used to generate a absolute url for authentication.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   */
  public function __construct(MetadataBubblingUrlGenerator $url_generator, array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $config_factory);

    $this->urlGenerator = $url_generator;
  }

  /**
   * {@inheritdoc}
   */
  public function initSdk() {
    $class_name = '\LinkedIn\LinkedIn';
    if (!class_exists($class_name)) {
      throw new SocialApiException(sprintf('The PHP SDK for Linkedin Services could not be found. Class: %s.', $class_name));
    }

    /* @var \Drupal\social_auth_linkedin\Settings\LinkedinAuthSettings $settings */
    $settings = $this->settings;

    // Gets the absolute url of the callback.
    $redirect_uri = $this->urlGenerator->generateFromRoute('social_auth_linkedin.callback', [], ['absolute' => TRUE]);

    // Creates a and sets data to Linkedin_Client object.
    $client = new LinkedIn([
      'api_key' => $settings->getClientId(),
      'api_secret' => $settings->getClientSecret(),
      'callback_url' => $redirect_uri,
    ]);

    return $client;
  }

}
