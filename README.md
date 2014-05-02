# Doctrine Entity Logger

This module have the responsability to log Doctrine Operations on Entities like Insert, Update and Delete

To enable the module, just copy the dist configuration to your config/autoload directory and edit the configurations as you like

```bash
cp vendor/caferrari/caf-doctrine-logger/config/module.config.php.dist config/autoload/doctrine_logger.global.php
```

After that, add the module to your 'config/application.config.php'

```php
<?php
// ...
'modules' => array(
    // ...
    'CafDoctrineLogger'
)
```

To enable the logging on your entities, just implement the interface `CafDoctrineLogger\Loggable`:

```php
<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use CafDoctrineLogger\Loggable;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 * @ORM\HasLifecycleCallbacks
 */
class Category implements Loggable
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    protected $name;

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
```

Make sure that the php has permissions to write in the query log directory.
