<?php

/**
 * @class ArrangeIndustries
 */
class ArrangeIndustries
{
    /**
     * @return array
     */
    public function handle(): array
    {
        $rawIndustries = $this->getIndustries();
        [$parents, $children] = $this->getParentAndChildrenIndustries($rawIndustries);

        return $this->putChildrenIntoCorrespondingParents($parents, $children);
    }

    /**
     * @return array
     */
    protected function getIndustries(): array
    {
        return json_decode(file_get_contents(__DIR__ . '/industries.json'), true);
    }

    /**
     * @param array $rawIndustries
     * @return array[]
     */
    protected function getParentAndChildrenIndustries(array $rawIndustries): array
    {
        $parents  = [];
        $children = [];

        foreach ($rawIndustries as $industry) {
            $code = $industry['advertiser_category_id'];
            $name = $industry['advertiser_category_name'];

            $isParent = !str_contains($code, '_');

            if ($isParent) {
                $parents[] = compact('code', 'name');
            } else {
                $children[] = compact('code', 'name');
            }
        }

        return [$parents, $children];
    }

    /**
     * @param array $parents
     * @param array $children
     * @return array
     */
    protected function putChildrenIntoCorrespondingParents(array $parents, array $children): array
    {
        $industries = [];

        foreach ($parents as $parent) {
            $industries[$parent['code']] = [
                'code'     => $parent['code'],
                'name'     => $parent['name'],
                'children' => [],
            ];
        }

        foreach ($children as $child) {
            $parentCode = explode('_', $child['code'])[0];

            $industries[$parentCode]['children'][] = $child;
        }

        return $industries;
    }
}
