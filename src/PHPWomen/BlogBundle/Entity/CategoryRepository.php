<?php

namespace PHPWomen\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 */
class CategoryRepository extends EntityRepository
{
    /**
     *
     * @return array
     */
    public function fetchCategoriesWithPosts()
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT DISTINCT c
                FROM PHPWomenBlogBundle:Category c
                INNER JOIN PHPWomenBlogBundle:Post p
                WHERE c.id = p.category
                ORDER BY c.name asc
            ');
        return $query->getResult();
    }

}
