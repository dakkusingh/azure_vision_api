<?php

namespace Drupal\azure_vision_api\Service;

use Drupal\azure_cognitive_services_api\Service\Client as AzureClient;
use Drupal\Core\Config\ConfigFactory;

/**
 *
 * @property \Drupal\Core\Config\Config|\Drupal\Core\Config\ImmutableConfig config
 */
class Vision {

  /**
   * @var \Drupal\azure_cognitive_services_api\Service\Client
   */
  private $azureClient;

  /**
   * @var \Drupal\Core\Config\ConfigFactory
   */
  private $configFactory;

  /**
   * Constructor for the Vision API class.
   *
   * @param \Drupal\Core\Config\ConfigFactory $configFactory
   */
  public function __construct(ConfigFactory $configFactory, AzureClient $azureClient) {
    $this->config = $configFactory->get('azure_vision_api.settings');
    $this->azureClient = $azureClient;
  }

  /**
   * See https://westus.dev.cognitive.microsoft.com/docs/services/56f91f2d778daf23d8ec6739/operations/56f91f2e778daf14a499e1ff.
   *
   * @param $photoUrl
   * @param bool $visualFeatures
   * @param bool $details
   *
   * @return bool|mixed
   */
  public function analyze($photoUrl,
                          $visualFeatures = TRUE,
                          $details = TRUE
  ) {
    $uri = $this->config->get('api_url') . 'analyze';
    $params = [];

    if ($details) {
      $allowedDetails = $this->config->get('allowed_details');
      $params['details'] = implode(',', $allowedDetails);
    }
    if ($visualFeatures) {
      $allowedVisualFeatures = $this->config->get('allowed_visual_features');
      $params['visualFeatures'] = implode(',', $allowedVisualFeatures);
    }

    if (count($params) > 0) {
      $queryString = http_build_query($params);
      $uri = urldecode($uri . '?' . $queryString);
    }

    $body = ['json' => ['url' => $photoUrl]];
    $result = $this->azureClient->doRequest('vision', $uri, 'POST', $body);

    return $result;
  }

  /**
   *
   */
  public function describe() {}

  /**
   *
   */
  public function generateThumbnail() {}

  /**
   *
   */
  public function ocr() {}

  /**
   *
   */
  public function models() {}

  /**
   *
   */
  public function recognizeText() {}

  /**
   *
   */
  public function tag() {}

}
