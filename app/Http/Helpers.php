<?php
function errorClass($name)
{
    return errorExists($name) ? "is-invalid" : null;
}

function errorText($name)
{
    return errorExists($name) ? "<div><small class=\"text-danger\">" . error($name) . "</small></div>" : null;
}

function sidebarActive($routeName, $contain = false)
{
    return equalUrl(route($routeName), $contain) ? "active" : null;
}

function navActive($routeName)
{
    return equalUrl(route($routeName)) ? "active" : null;
}

function sidebarAngle($routeName, $contain = true)
{
    return equalUrl(route($routeName), $contain) ? "bi-chevron-down" : "bi-chevron-left";
}

function sidebarLinkActive($routeName)
{
    return equalUrl(route($routeName)) ? "sidebar-link-active" : null;
}

function sidebarDropDownActive($routeNames, $contain = false)
{
    foreach ($routeNames as $routeName)
        if (equalUrl(route($routeName), $contain))
            return "sidebar-group-link-active";
    return null;
}