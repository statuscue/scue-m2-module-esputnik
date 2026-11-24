# ESputnik Magento2 integration

This module provides integration with eSputnik service. Check [API documentation](https://esputnik.com/api/methods.html) for more information.

Navigation:
- Services
  - Broadcast 
    - [x] Email
    - [x] SMS
    - [ ] Viber
    - [ ] Web PUSH
    - [ ] Mobile PUSH
  - Subscription
  - Events
    - [x] Order status changed
    - [ ] New customer with order
    - [ ] Customer logged in
    - [ ] Customer registered
- Repositories
  - Contact

## Services

### Broadcast

This service lets you broadcast message on different channels. Supported channels now: 
- Email
- SMS

1. Provide the service through DI container:

```php
public function __construct(\Scue\ESputnik\Service\Broadcast $broadcast) {...}
```

2. Call a broadcast channel with needed contact, text and provide specific data for called channel. For example, how to send email:

```php
...
$this->broadcast->send('email', 'test@example.com', 'Welcome text!');
...
```

As a contact you can also provide an array of contacts:

```php
...
$this->broadcast->send('email', ['test@example.com', 'test2@example.com'], 'Welcome text!');
...
```

Example for SMS sending:

```php
...
$this->broadcast->send('sms', [380999999999], 'Thanks for an order');
...
```

You can also send templates configured via admin. You need use templates factory. Example with SMS authorization template:

```php
...
public function __construct(private \Scue\ESputnik\Model\Broadcast\TemplateFactory $templateFactory) {...}
...
$template = $this->templateFactory->create(EnumTemplate::SMS_CUSTOMER_AUTHORIZATION->value, [
    'code' => 8889
]);
$this->service->send(
    'sms',
    [380999999999],
    $template
);
...
```

Send instant message (avoid queue):
```php
...
$this->service->send(
    'sms',
    380999999999,
    'Message to send',
    ['queue' => false]
);
...
```

For more information check current service configuration (can add, remove, find correct channel name):
```xml
<type name="Scue\ESputnik\Service\Broadcast\ChannelFactory">
    <arguments>
        <argument name="channels" xsi:type="array">
            <item name="email" xsi:type="string">Scue\ESputnik\Service\Broadcast\Channel\Email</item>
            <item name="sms" xsi:type="string">Scue\ESputnik\Service\Broadcast\Channel\Sms</item>
        </argument>
    </arguments>
</type>
```

### Subscription

To use subscription model provide it through DI container:

```php
public function __construct(\Scue\ESputnik\Api\Subscription $subscription) {...}
```

This class has main method `subscribe` which expects array of subscription data. Usage example:

```php
...
$this->subscription->subscribe([
    'email' => 'test@example.com',
    'id' => '99999999',
    'additional' => [...]
]);
...
```
Email here will be populated to channels. Id - is subscription id at admin panel.
Sms field will be populated to channels filed:
```php
...
$this->subscription->subscribe([
    'email' => 'test@example.com',
    'sms' => '390999999999',
    'id' => '99999999',
    'additional' => [...]
]);
...
```
Subscription already works for controller, send POST request to `esputnik/subscribe` url address.

API integration is also provided(in case if you would like to make subscription via ajax request). Send POST request to `[domain_name]/rest/default/V1/esputnik/subscribe` with body
```json
{
    "subscription": {
        "email": "test@example.com",
        "id": "99999999",
        "additional": {
            "firstName": "Scue",
            "lastName": "Scue"
        }
    }
}
```

### Events
API requires data for different events(order status changed/customer logged in etc.).

Provide event through class constructor:
```php
public function __construct(\Scue\ESputnik\Api\Event $event) {...}
```

Check basic event config for "order status changed" event:
```xml
<virtualType name="ESputnikOrdersEvent" type="Scue\ESputnik\Service\Event">
    <arguments>
        <argument name="type" xsi:type="const">Scue\ESputnik\Service\Event::EVENT_TYPE_ORDERS</argument>
        <argument name="mapper" xsi:type="object">Scue\ESputnik\Service\Event\Mapper\Order</argument>
    </arguments>
</virtualType>
```

And then populate it in magento way:

```xml
<type name="Scue\ESputnik\Plugin\Order">
    <arguments>
        <argument name="event" xsi:type="object">ESputnikOrdersEvent</argument>
    </arguments>
</type>
```

## Repositories
Repositories provide simple access to different API entities.

### Contact
To use contact repository provide it through DI container:

```php
public function __construct(\Scue\ESputnik\Api\Repository\ContactRepository $repository) {...}
```

You can simply get info about the contact by methods `getByEmail`, `getByTelephone`, `getByExternalId`:
```php
...
$this->repository->getByEmail('test@example.com');
...
```
Check available response by API doc.