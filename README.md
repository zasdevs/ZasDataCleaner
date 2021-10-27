# ZasDataCleaner

ZasDataCleaner is a auto directory cleaner PHP class.

### Installation
To utilize this class, first import ZasDataCleaner.php into your project, and require it.
```php
require_once 'ZasDataCleaner.php';
```

### Initialization
Simply create an instanace and start using it.
```php
/**
 * 'zas' is a folder name where the files are stored.
 * '40%' always available space/storage.
 * '120_GB' is the folder assigned storage limit.
 */
$cleaner = new ZasDataCleaner( 'zas', '40%', '120_GB' );
```

### Usage
To get that how many files are in the folder:
```php
$cleaner->file_count_in_folder();
```

If you set 40% then, how many files will be deleted?
```php
$cleaner->delete_limit;
```

To check the assigned memory limit in ```120_GB to bytes```:
```php
$cleaner->assigned;
```

To check the folder memory size in bytes:
```php
$cleaner->folder_size();
```

To display the available files in the folder ```array response```:
```php
$cleaner->display_available_files();
```

```
Array
(
    [0] => File (1).txt
    [1] => File (2).txt
    [2] => File (3).txt
)
```

This function always manage your folder with 40% cleaning:
```php
$cleaner->delete_files();
```

### Example
Call the class for folder management:
```php
require_once 'ZasDataCleaner.php';

$cleaner = new ZasDataCleaner( 'zas', '40%', '120_GB' );

$cleaner->delete_files();
```

#### Thanks you have done your folder memory is always 40% available with this code.
