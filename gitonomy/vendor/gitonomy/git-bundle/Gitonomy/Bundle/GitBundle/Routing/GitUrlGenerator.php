<?php

namespace Gitonomy\Bundle\GitBundle\Routing;

use Gitonomy\Git\Repository;

/**
 * Default implementation uses the directory's name.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class GitUrlGenerator extends AbstractGitUrlGenerator
{
    /**
     * @inheritdoc
     */
    public function getName(Repository $repository)
    {
        $name = basename($repository->getPath());

        if (preg_match('/^(.*)\.git$/', $name, $vars)) {
            return $vars[1];
        }

        return $name;
    }
}
