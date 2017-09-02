<?php
declare(strict_types=1);

namespace Roave\BetterReflection\TypesFinder;

use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Type;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use Roave\BetterReflection\Reflection\ReflectionFunctionAbstract;
use Roave\BetterReflection\TypesFinder\PhpDocumentor\NamespaceNodeToReflectionTypeContext;

class FindReturnType
{
    /**
     * @var ResolveTypes
     */
    private $resolveTypes;

    /**
     * @var DocBlockFactory
     */
    private $docBlockFactory;

    /**
     * @var NamespaceNodeToReflectionTypeContext
     */
    private $makeContext;

    public function __construct()
    {
        $this->resolveTypes    = new ResolveTypes();
        $this->docBlockFactory = DocBlockFactory::createInstance();
        $this->makeContext     = new NamespaceNodeToReflectionTypeContext();
    }

    /**
     * Given a function, attempt to find the return type.
     *
     * @param ReflectionFunctionAbstract $function
     * @param Use_[] $useStatements
     * @return Type[]
     */
    public function __invoke(ReflectionFunctionAbstract $function, ?Namespace_ $namespace) : array
    {
        $docComment = $function->getDocComment();

        if ('' === $docComment) {
            return [];
        }

        $context = $this->makeContext->__invoke($namespace);

        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Return_[] $returnTags */
        $returnTags = $this->docBlockFactory->create(
            $docComment,
            $context
        )->getTagsByName('return');

        foreach ($returnTags as $returnTag) {
            return $this->resolveTypes->__invoke(\explode('|', (string) $returnTag->getType()), $context);
        }

        return [];
    }
}
