# Magento 2 Product Slider by Mageplaza

[![Latest Stable Version](https://poser.pugx.org/mageplaza/magento-2-product-slider/v/stable)](https://packagist.org/packages/mageplaza/magento-2-product-slider)
[![Total Downloads](https://poser.pugx.org/mageplaza/magento-2-product-slider/downloads)](https://packagist.org/packages/mageplaza/magento-2-product-slider)

## IMPORTANT

**All features of Product Slider now includes in Auto Related Products**

*THIS EXTENSION DEVELOPED FOR DEVELOPERS, IT REQUIRES INSERT A SNIPPET INTO LAYOUTS, TEMPLATE FILES. IF YOU ARE ADMIN, YOU NEED HELPS FROM YOUR DEVELOPERS.* Consider to learn more about [Auto Related Products here](https://www.mageplaza.com/magento-2-automatic-related-products/)

## 1. Documentation

- Installation Guide: https://www.mageplaza.com/install-magento-2-extension/
- User Guide: https://www.mageplaza.com/magento-2-product-slider-extension/
- Download from our Live site: https://www.mageplaza.com/magento-2-product-slider-extension/
- Get Support: https://github.com/mageplaza/magento-2-product-slider/issues
- Contribute on Github: https://github.com/mageplaza/magento-2-product-slider/
- License: https://www.mageplaza.com/LICENSE.txt
- Changelog: https://www.mageplaza.com/changelog/m2-product-slider.txt

## 2. How to install

### ✓ Install via composer (recommend)

Run the following command in Magento 2 root folder:

```
composer require mageplaza/magento-2-product-slider
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

### ✓ Install ready-to-paste package

- Download latest version at [Mageplaza Product Slider](https://www.mageplaza.com/magento-2-product-slider-extension/)
-  [Installation guide](https://www.mageplaza.com/install-magento-2-extension/)

## 3. FAQs

#### Q: I got error: `Mageplaza_Core has been already defined`
A: Read solution: https://github.com/mageplaza/module-core/issues/3

#### Q: My site is down
A: Please follow this guide: https://www.mageplaza.com/blog/magento-site-down.html


## 4. Contribute to this module

Feel free to **Fork** and contrinute to this module and create a pull request so we will merge your changes to `master` branch.







## 6. Product Slider Introduction

Magento 2 Product Slider module allows showing all suggested products friendly that match to customer’s expectation.

- Support: Featured, On Sale, New Products, Category ID Products
- Responsive slider
- Casual OWL slider options (all options)
- Insert product slider anywhere on your store
- Easy to install and setup

Supporting the following Product slider types:

- On Sale Products Slider
- New Products Slider
- Most Viewed Products Slider
- Featured Products Slider



## How does it work?

When you enable Magento 2 Product Slider module and create the product sliders in the configuration, lists of your products will slide out in the lively and attractive way on the storefront with the animation effects you have chosen before. Along with product image, your clients can also see the price information, rating for the product and even Add to Cart button to proceed to the checkout whenever they need.

![magento 2 product slider](https://www.mageplaza.com/assets/img/extensions-images/magento-2-product-slider/product-slider-effect.gif)

### Supports multiple slider types

Product Slider Magento 2 extension supports a drop-down list of slider types:

- On Sale Products Slider
- New Products Slider
- Most Viewed Products Slider
- Featured Products Slider

### Fully responsive design

![magento 2 responsive product slider](https://www.mageplaza.com/assets/img/extensions-images/magento-2-product-slider/responsive.jpg)

With Product Slider for Magento 2, the slider display is free to custom how to be friendly with any touch devices like iPhone and iPad. You can set the breakpoint, the large, medium and small display to design the best performance.

### Insert Product Slider anywhere

Not only can you create unlimited sliders but Product Slider module also allows placing the sliders like content or sidebar additional at the top or bottom of any page on your website flexibly. The options you can put the sliders are:

- Home Page
- Category Page
- Product Page
- Cart Page
- Checkout Page
- Customer Page


### Custom product slider

Along with the performance of the slider, you can make individual product elements visible like name, price, rating, add to cart button, wishlist and compare links, etc. With a full of product information, it is easy to have a perfect look as well as create a new purchase quickly. If not, let hide them.


### Full feature list of Product Slider

- Display Featured Products
- Display New Products
- Display Bestsellers Products
- Display On sale Products
- Display Most viewed Products (coming soon)
- Create unlimited product slider
- Fully responsive slider
- Insert anywhere
- Insert easily: XMl, phtml file or widget
- Display products in a default basic grid
- Slider effects
- Pick product manually
- Effective configuration
- Setting in each slider
- Easy to install and setup
- Touch swipe for mobile devices
- Insert to CMS Pages, CMS Static or Homepage
- Display in Sidebar
- Support all browsers: Firefox, Chrome, IE, Safari Opera.

### Other features

- Display Featured Products
- Open source 100%.
- Easy to install and configure.
- User-friendly interface.
- Fully compatible with Mageplaza extensions
- Supports multiple stores.
- Supports multiple languages.


## 7. User Guide



Welcome to User Guide of [Magento 2 Product
Slider](https://www.mageplaza.com/magento-2-product-slider-extension/)
extension. Actually, this is developer guide, not user guide. Because
this extension is developed for Developers.

Why Mageplaza developed this module for Developers
--------------------------------------------------

-   **Optimize performnace** do not slow your Magento 2 store by adding
    banner slider everywhere, every positions (\~36 positions) on the
    site.
-   Details and quality documentations for developers.
-   Implement with ease.
-   No need, no added.
-   Free, Open-source.

List of Available Blocks
------------------------

-   `Mageplaza\Productslider\Block\OnSaleProduct` : **On Sale Products**
-   `Mageplaza\Productslider\Block\CategoryId` : **CategoryId** , get
    products from specific category id
-   `Mageplaza\Productslider\Block\FeaturedProducts` : **Featured
    Products**
-   `Mageplaza\Productslider\Block\NewProducts` : **New Products**
-   More (coming soon). Request more at `support@mageplaza.com`

How to use
----------

We will show you how to use insert Product Slider in CMS page, Static
Block, XML File, XML Data, .phtml file.

**1. CMS Page, CMS Static Block**

    {{block class="Mageplaza\Productslider\Block\NewProducts" template="Mageplaza_Productslider::productslider.phtml" products_count="8" heading="New Products" description="Here is your new products description"}}

You can paste the above block of snippet into CMS page such as Home page
or specific CMS page or any CMS static block in Magento 2.

**2. XML File , XML Data**

```
    <block class="Mageplaza\Productslider\Block\NewProducts" name="product.slider.  newproduct" template="productslider.phtml">
      <arguments>
        <argument name="products_count" xsi:type="number">8</argument>
        <argument name="margin" xsi:type="string">5</argument>
        <argument name="heading" xsi:type="string">New Products</argument>
        <argument name="description" xsi:type="string">Here is your new products   description </argument>
      </arguments>
    </block>
```

Open layout file such as `category_catalog_view.xml` or XML Data and
insert the above block of code, then all categories page will be added
the product slider with 8 new products.

**3. .phtml file**

    <?php echo $block->getLayout()->createBlock('Mageplaza\Productslider\Block\NewProducts')->setTemplate('productslider.phtml')->toHtml();?>

Open a `.phtml` file and insert where you want to display the product
slider.

Custom Style
------------

You can custom template file at
`app/code/Mageplaza/Productslider/view/frontend/templates/productslider.phtml`

> **important**
>
> In your theme, custom design, you should copy this file into your
> package and edit them instead of directly edit it.


## CHANGELOG 


### Product Slider v1.2.3
Released on  2017-06-30
Release notes: 





### Product Slider v1.2.2
Released on  2017-04-28
Release notes: 

- Fix bug compilation errors



### Product Slider v1.2.1
Released on  2017-04-24
Release notes: 

- Edit composer.json to require mageplazamodule-core instead of mageplazacore-m2



### Product Slider v1.2.0
Released on  2017-04-13
Release notes: 

- Added Recent, Bestseller product slider
- Added a new simple template Developer can custom it.



### Product Slider v1.1.3
Released on  2017-04-09
Release notes: 





### Product Slider v1.1.2.1
Released on  2016-12-20
Release notes: 




## Mageplaza extensions on Magento Marketplace, Github


☞ [Magento 2 One Step Checkout extension](https://marketplace.magento.com/mageplaza-magento-2-one-step-checkout-extension.html)

☞ [Magento 2 SEO Module](https://marketplace.magento.com/mageplaza-magento-2-seo-extension.html)

☞ [Magento 2 Blog extension](https://marketplace.magento.com/mageplaza-magento-2-blog-extension.html)

☞ [Magento 2 Layered Navigation extension](https://marketplace.magento.com/mageplaza-layered-navigation-m2.html)

☞ [Magento One Step Checkout](https://github.com/magento-2/one-step-checkout)

☞ [Magento 2 Blog on Github](https://github.com/mageplaza/magento-2-blog)

☞ [Magento 2 Social Login on Github](https://github.com/mageplaza/magento-2-social-login)

☞ [Magento 2 SEO on Github](https://github.com/mageplaza/magento-2-seo)

☞ [Magento 2 SMTP on Github](https://github.com/mageplaza/magento-2-smtp)

☞ [Magento 2 Product Slider on Github](https://github.com/mageplaza/magento-2-product-slider)

☞ [Magento 2 Banner on Github](https://github.com/mageplaza/magento-2-banner-slider)





