/!\ Old stuff, might need to be updated.

# PHP Oanda Api Client

A PHP client for [Oanda's API](http://developer.oanda.com/). Tested on Google App engine ([PHP runtime environment](https://developers.google.com/appengine/docs/php/))

## Configuration
Rename config.template.php to config.php and change the named constants.

```php
define(TOKEN_OANDA, "PASTE YOUR TOKEN HERE");
define(ACCOUNT_NUMBER,"123456");
define(ACCOUNT_TYPE,"sandbox"); //sandbox, practice or real
define(USERNAME,"PASTE YOUR USERNAME");
```

## Usage

Include the config file and the class library
```php
include("config.php");
include("class.oandaApi.php");
```

Create an instance of oandaAPI and set the account number.
```php
$oanda = new oandaApi(TOKEN_OANDA , ACCOUNT_TYPE, false);
// set the third parameter to true for debug mode
$oanda->setAccountId(ACCOUNT_NUMBER);
```

To get current prices:
```php
$oanda->getCurrentPrices(array("EUR_USD", "USD_CHF"));
```

To open a market order (buy 10k on EURUSD)
```php
$oanda->createOrder("EUR_USD", 10000, "buy", "market");
```

To open a limit order (sell 20k on GBPUSD at 1.6800 with a stop loss at 1.6850, a take profit at 1.6700 and a trailing stop at 50 pips, expire in one hour)
```php
$expiry=gmdate("Y-m-d\TH:i:s\Z", strtotime("+ 1 hour"));
$oanda->createOrder("GBP_USD", 20000, "sell", "limit", $expiry, 1.6800, 1.6850, 1.6700, 50);
```

Close all positions on EURUSD
```php
$oanda->closePosition("EUR_USD")
```

Please check /test/price.php for more examples.

## Warning
This client for Oanda's API allows you to create orders on your trading account. It results a HIGH RISK for not expert developers. You must test your code on a practice environment before use it on your real account.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

