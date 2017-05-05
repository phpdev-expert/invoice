<?php

function addon_path($path = '')
{
    return base_path('custom/addons').($path ? DIRECTORY_SEPARATOR . $path : $path);
}