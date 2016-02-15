<?php
return sprintf('<input type="checkbox" name="%s" id="%s" %s placeholder="" />', $fi['name'], $fi['id'], checked('on', $fi['value'], false));
