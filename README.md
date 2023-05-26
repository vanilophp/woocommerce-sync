# Vanilo + WooCommerce Data Exchange Module

[![Tests](https://img.shields.io/github/actions/workflow/status/vanilophp/woocommerce-sync/tests.yml?branch=master&style=flat-square)](https://github.com/vanilophp/woocommerce-sync/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/woocommerce-sync.svg?style=flat-square)](https://packagist.org/packages/vanilo/woocommerce-sync)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/woocommerce-sync.svg?style=flat-square)](https://packagist.org/packages/vanilo/woocommerce-sync)
[![StyleCI](https://styleci.io/repos/432689407/shield?branch=master)](https://styleci.io/repos/432689407)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

## Installation

> Make sure your application has [Vanilo Framework or the Product Module](https://vanilo.io/docs/) installed

Install the package with composer:

```bash
composer require vanilo/woocommerce-sync
```

## Usage

```bash
php artisan vanilo:woo:import wc-product-export-XXXX.csv
```
