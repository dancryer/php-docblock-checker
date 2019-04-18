#!/usr/bin/env bash

echo ../megatron/controllers/AjaxInventoryController.php | php -dxdebug.remote_enable=1 -dxdebug.remote_mode=req -dxdebug.remote_port=9000 -dxdebug.remote_host=127.0.0.10 ./bin/phpdoccheck --from-stdin