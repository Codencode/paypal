{*
* 2007-2021 PayPal
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author 2007-2021 PayPal
*  @copyright PayPal
*  @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
*
*}

<!-- Start shortcut. Module Paypal -->
{block name='head'}
  <script>
     {foreach from=$JSvars key=varName item=varValue}
        var {$varName} = {$varValue|json_encode nofilter};
     {/foreach}
  </script>
{/block}

{block name='content'}{/block}

{block name='js'}
    {if isset($JSscripts) && is_array($JSscripts) && false === empty($JSscripts)}
        {foreach from=$JSscripts key=keyScript item=JSscriptAttributes}
          <script>
              var script = document.querySelector('script[data-key="{$keyScript}"]');

              if (null == script) {
                  var newScript = document.createElement('script');
                  {foreach from=$JSscriptAttributes key=attrName item=attrVal}
                  newScript.setAttribute('{$attrName}', '{$attrVal nofilter}');
                  {/foreach}

                  newScript.setAttribute('data-key', '{$keyScript}');
                  document.body.appendChild(newScript);
              }
          </script>
        {/foreach}
    {/if}
{/block}

{block name='init-button'}
  <script>
      function waitPaypalIsLoaded() {
          if (typeof totPaypalSdkButtons === 'undefined' || typeof Shortcut === 'undefined') {
              setTimeout(waitPaypalIsLoaded, 200);
              return;
          }

          Shortcut.init();
          Shortcut.initButton();
      }

      waitPaypalIsLoaded();
  </script>
{/block}
<!-- End shortcut. Module Paypal -->



