# Magento 2 Added to Cart Popup

![alt text](https://raw.githubusercontent.com/magekey/module-adc-popup/master/docs/images/popup.png)

## Features:

- Show up popup when product added to cart.

## Installing the Extension

    composer require magekey/module-adc-popup

## Deployment

    php bin/magento maintenance:enable                  #Enable maintenance mode
    php bin/magento setup:upgrade                       #Updates the Magento software
    php bin/magento setup:di:compile                    #Compile dependencies
    php bin/magento setup:static-content:deploy         #Deploys static view files
    php bin/magento cache:flush                         #Flush cache
    php bin/magento maintenance:disable                 #Disable maintenance mode

## Versions tested
> 2.2.2
