# cglm-php-ffi
 [recp/cglm](https://github.com/recp/cglm) binding for PHP using FFI

Tested with PHP-cli 8.0.x under Linux.

- the cglm C API is encapsulated into a PHP class `GLM` which only contains `const` and `static` members ;
- it autoinit uppon ` include_once("./include/GLM.php"); ` ;
- depeding on your OS, it will try to load `libcglm.so` or `libcglm.dll` from `./lib/` (default), or from the directory defined in `FFI_LIB_DIR` ;
- a `__callStatic()` method is used to call C functions using FFI. Example : `` GLM::vec3_normalize( $vec ); `` ;
- if required, it is possible to override a C function by adding a ` public static function ` with the same name into the class. This can be used to simply the C API and the usage of FFI with some functions that requires pointers.
- helpers can be added to the ` GLM:: ` API.

## /!\ Performance :

Using the ` __staticCall ` encapsulation method makes life easier, but it represents a great loss of performance.

If high perf are required, I recommand using ` GLM::$ffi->glmc_xxxxx() ` direct calls.

## TODO :

- add some helpers that would make the API easier to use in PHP ...
