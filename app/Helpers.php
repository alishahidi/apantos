<?php
function errorClass($name)
{
    return errorExists($name) ? "is-invalid" : null;
}

function errorText($name)
{
    return errorExists($name) ? "<div><small class=\"text-danger\">" . error($name) . "</small></div>" : null;
}