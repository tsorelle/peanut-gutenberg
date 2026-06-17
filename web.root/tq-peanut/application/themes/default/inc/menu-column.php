<?php
/** @var \Nutshell\cms\SiteMap $sitemap */
/** @var int $colsize */
/** @var string $menutype */
/** @var string $menutitle */
/** @var string $menuclasses */

print sprintf("<div class='col-md-%s'>\n",$colsize);
if (!empty($menutitle)) { ?>
    <div class="menu-title">
        <h3>
            <?php print $menutitle ?>
        </h3>
    </div>
<?php
}
if (!empty($menuclasses)) {
    print "<div class='$menuclasses'>";
}
if ($menutype === 'sibling') {
    $sitemap->printSiblingMenu();
}
else {
    $sitemap->printChildMenu();
}
if (!empty($menuclasses)) {
    print '</div>';
}
print '</div>';
