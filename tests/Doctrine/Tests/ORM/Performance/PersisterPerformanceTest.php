<?php

namespace Doctrine\Tests\ORM\Performance;

use Doctrine\ORM\Query;
use Doctrine\Tests\Models\CMS\CmsUser;
use Doctrine\Tests\Models\CMS\CmsGroup;
use Doctrine\Tests\Models\CMS\CmsArticle;
use Doctrine\Tests\OrmFunctionalTestCase;

/**
 * @group performance
 */
class PersisterPerformanceTest extends OrmFunctionalTestCase
{
    protected function setUp()
    {
        $this->useModelSet('cms');
        parent::setUp();
    }

    public function testFindCmsArticle()
    {
        $author = new CmsUser();
        $author->name = "beberlei";
        $author->status = "active";
        $author->username = "beberlei";
        $this->_em->persist($author);

        $ids = [];
        for ($i = 0; $i < 100; $i++) {
            $article = new CmsArticle();
            $article->text = "foo";
            $article->topic = "bar";
            $article->user = $author;
            $this->_em->persist($article);
            $ids[] = $article;
        }
        $this->_em->flush();
        $this->_em->clear();

        $start = microtime(true);
        $articles = $this->_em->getRepository(CmsArticle::class)->findAll();
        echo "100 CmsArticle findAll(): " . number_format(microtime(true) - $start, 6) . "\n";

        $this->_em->clear();

        $start = microtime(true);
        $articles = $this->_em->getRepository(CmsArticle::class)->findAll();
        echo "100 CmsArticle findAll(): " . number_format(microtime(true) - $start, 6) . "\n";

        $this->_em->clear();

        $start = microtime(true);
        for ($i = 0; $i < 100; $i++) {
            $articles = $this->_em->getRepository(CmsArticle::class)->find($ids[$i]->id);
        }
        echo "100 CmsArticle find(): " . number_format(microtime(true) - $start, 6) . "\n";

        $this->_em->clear();

        $start = microtime(true);
        for ($i = 0; $i < 100; $i++) {
            $articles = $this->_em->getRepository(CmsArticle::class)->find($ids[$i]->id);
        }
        echo "100 CmsArticle find(): " . number_format(microtime(true) - $start, 6) . "\n";
    }

    public function testFindCmsGroup()
    {
        for ($i = 0; $i < 100; $i++) {
            $group = new CmsGroup();
            $group->name = "foo" . $i;
            $this->_em->persist($group);
        }
        $this->_em->flush();
        $this->_em->clear();

        $start = microtime(true);
        $articles = $this->_em->getRepository(CmsGroup::class)->findAll();
        echo "100 CmsGroup: " . number_format(microtime(true) - $start, 6) . "\n";

        $this->_em->clear();

        $start = microtime(true);
        $articles = $this->_em->getRepository(CmsGroup::class)->findAll();
        echo "100 CmsGroup: " . number_format(microtime(true) - $start, 6) . "\n";
    }

    public function testFindCmsUser()
    {
        for ($i = 0; $i < 100; $i++) {
            $user = new CmsUser();
            $user->name = "beberlei";
            $user->status = "active";
            $user->username = "beberlei".$i;
            $this->_em->persist($user);
        }

        $this->_em->flush();
        $this->_em->clear();

        $start = microtime(true);
        $articles = $this->_em->getRepository(CmsUser::class)->findAll();
        echo "100 CmsUser: " . number_format(microtime(true) - $start, 6) . "\n";

        $this->_em->clear();

        $start = microtime(true);
        $articles = $this->_em->getRepository(CmsUser::class)->findAll();
        echo "100 CmsUser: " . number_format(microtime(true) - $start, 6) . "\n";
    }
}
