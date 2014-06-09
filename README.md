#PHP Oanda Api Client

A client for Oanda's API. Tested on Google App engine ([PHP runtime environment](https://developers.google.com/appengine/docs/php/))

##Configuration
Rename config.template.php to config.php and change

```PHP
define(TOKEN_OANDA, "PASTE YOUR TOKEN HERE");
define(ACCOUNT_NUMBER,"123456");
define(ACCOUNT_TYPE,"sandbox"); //sandbox, practice or real
define(USERNAME,"PASTE YOUR USERNAME");
```


##Warning
This client for Oanda's API allows you to create orders on your trading account. It results a HIGH RISK for not expert developers. You must test your code on a practice environment before use it on your real account.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

