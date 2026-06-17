<?php

namespace Tops\cms\wordpress;
use Tops\db\TQuery;

class WordPressSiteMapBuilder
{

    public function build($menuName = 'main-menu') : \stdClass
    {
        $buildResult = new \stdClass();
        $buildResult->errors = array();
        $buildResult->success = false;
        $items = wp_get_nav_menu_items($menuName);
        if (!$items) {
            $buildResult->errors[] = "Menu '$menuName' not found or empty.";
            return $buildResult;
        }

        $tree = $this->buildTree($items);

        $xml = new \SimpleXMLElement('<site-map/>');
        $this->addXmlNodes($xml, $tree);

        $sourceFile = DIR_CONFIGURATION . '/sitemap.xml';
        if (file_exists($sourceFile)) {
            $sourceXml = simplexml_load_file($sourceFile);
            if ($sourceXml) {
                $this->mergeSourceNodes($xml, $sourceXml);
            }
        }

        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;
        $xmlString = $dom->saveXML();

        $outputFile = DIR_CONFIGURATION . '/wp-sitemap.xml';
        $success = file_put_contents($outputFile, $xmlString);

        if ($success) {
            $backupFile = DIR_CONFIGURATION . '/sitemap.xml';
            if (file_exists($backupFile)) {
                copy($backupFile, DIR_CONFIGURATION . '/sitemap-backup.xml');
            }
            rename($outputFile, $backupFile);

            $buildResult->success = true;
            $buildResult->outputFile = $backupFile;
        }
        else {
            $buildResult->errors[] = "Failed to write sitemap to $outputFile";
        }
        if (!empty($buildResult->errors)) {
            $buildResult->success = false;
        }
        return $buildResult;
    }

    private function buildTree(array &$items, $parentId = 0) : array
    {
        $branch = array();
        foreach ($items as &$item) {
            if ($item->menu_item_parent == $parentId) {
                $children = $this->buildTree($items, $item->ID);
                if ($children) {
                    $item->children = $children;
                }
                $branch[] = $item;
            }
        }
        return $branch;
    }

    private function getTagName($item, &$usedTags) : string
    {
        // Prioritize title as requested
        $title = $item->title ?? $item->post_title ?? '';
        $tagName = strip_tags($title);
        $tagName = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '', $tagName));

        if (empty($tagName) || is_numeric(substr($tagName, 0, 1))) {
            $tagName = 'item' . ($tagName ?: $item->ID);
        }

        $baseTagName = $tagName;
        $counter = 1;
        while (isset($usedTags[$tagName])) {
            $tagName = $baseTagName . ++$counter;
        }
        $usedTags[$tagName] = true;

        return $tagName;
    }

    private TQuery $repository;
    public function getRoleNamesForPath($path) : array
    {
        if (!isset($this->repository)) {
            $this->repository = new TQuery();
        }

        $sql = 'SELECT r.`roleName` FROM pnut_accesspaths p '.
            'JOIN pnut_accessroles r ON r.`pathId` = p.id WHERE p.`uri` = ?';

        $stmt = $this->repository->executeStatement($sql,[$path]) ;
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function addXmlNodes(\SimpleXMLElement $xml, array $items) : void
    {
        $usedTags = [];
        foreach ($items as $item) {
            $tagName = $this->getTagName($item, $usedTags);
            $node = $xml->addChild($tagName);

            $itemTitle = $item->title ?? $item->post_title ?? '';
            $icon = '';
            if (preg_match('/<i class=["\']([^"\']+)["\']/', $itemTitle, $matches)) {
                $icon = $matches[1];
            }

            $node->addAttribute('title', trim(strip_tags($itemTitle)));

            $description = $item->description;
            if ($description) {
                $description = explode("\n", $description)[0];
                $description = trim(strip_tags($description));
            }
            $node->addAttribute('description', $description);

            $uri = $item->url;
            if (str_starts_with($uri, 'http')) {
                $uri = parse_url($uri, PHP_URL_PATH);
                if (empty($uri)) {
                    $uri = '/';
                }
            }

            $uri = trim($uri, '/');
            $roleNames = $this->getRoleNamesForPath($uri);
            $roles = implode(',', $roleNames);
            $node->addAttribute('roles', $roles);

            $node->addAttribute('uri', $uri);

            // Check for icon in classes if not found in title
            if (empty($icon) && !empty($item->classes)) {
                $iconClasses = [];
                foreach ($item->classes as $class) {
                    if (str_starts_with($class, 'fa-') || $class === 'fas' || $class === 'fab' || $class === 'far') {
                        $iconClasses[] = $class;
                    }
                }
                if (!empty($iconClasses)) {
                    $icon = implode(' ', $iconClasses);
                }
            }

            if (!empty($icon)) {
                $node->addAttribute('icon', $icon);
            }

            if (!empty($item->children)) {
                $this->addXmlNodes($node, $item->children);
            }
        }
    }

    private function mergeSourceNodes(\SimpleXMLElement $target, \SimpleXMLElement $source) : void
    {
        foreach ($target->children() as $node) {
            $uri = (string)$node['uri'];
            if ($node->count() == 0 && !empty($uri)) {
                $sourceNode = $source->xpath("//*[@uri='$uri']");
                if (!empty($sourceNode)) {
                    $sourceNode = $sourceNode[0];
                    if ($sourceNode->count() > 0) {
                        foreach ($sourceNode->children() as $child) {
                            $this->appendXML($node, $child);
                        }
                    }
                }
            }
            $this->mergeSourceNodes($node, $source);
        }
    }

    private function appendXML(\SimpleXMLElement $to, \SimpleXMLElement $from) : void
    {
        $toDom = dom_import_simplexml($to);
        $fromDom = dom_import_simplexml($from);
        $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
    }


}