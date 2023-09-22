<?php

namespace PhpDocBlockChecker\Check;

use PhpDocBlockChecker\FileInfo;
use PhpDocBlockChecker\Status\StatusType\Warning\ParamMismatchWarning;
use PhpDocBlockChecker\Status\StatusType\Warning\ParamMissingWarning;

class ParamCheck extends Check
{

    /**
     * @param FileInfo $file
     */
    public function check(FileInfo $file)
    {
        foreach ($file->getMethods() as $name => $method) {
            $docblock = $method->getDocblock();
            // If the docblock is inherited, we can't check for params and return types:
            if ($docblock->isInherited()) {
                continue;
            }

            foreach ($method->getParams() as $param => $paramType) {
                if (!$docblock->hasParam($param)) {
                    $this->fileStatus->add(
                        new ParamMissingWarning($file->getFileName(), $name, $method->getLine(), $name, $param)
                    );
                    continue;
                }

                $docBlockType = $docblock->getParam($param);

                foreach ($paramType->getTypes() as $type) {
                    if (!$docBlockType->hasType($type)) {
                        $this->fileStatus->add(
                            new ParamMismatchWarning(
                                $file->getFileName(),
                                $name,
                                $method->getLine(),
                                $name,
                                $param,
                                $paramType->toString(),
                                $docBlockType->toString(),
                            )
                        );

                        continue 2;
                    }
                }

                if (
                    $paramType->isNullable() !== $docBlockType->isNullable()
                    || $paramType->isVariadic() !== $docBlockType->isVariadic()
                ) {
                    $this->fileStatus->add(
                        new ParamMismatchWarning(
                            $file->getFileName(),
                            $name,
                            $method->getLine(),
                            $name,
                            $param,
                            $paramType->toString(),
                            $docBlockType->toString(),
                        )
                    );
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function enabled()
    {
        return !$this->config->isSkipSignatures();
    }
}
