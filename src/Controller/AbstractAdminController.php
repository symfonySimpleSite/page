<?php

namespace SymfonySimpleSite\Page\Controller;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Component\Form\Form;
use SymfonySimpleSite\Menu\Entity\Interfaces\MenuInterface;
use SymfonySimpleSite\Menu\Repository\MenuRepository;
use SymfonySimpleSite\Page\Entity\Interfaces\ImageInterface;

class AbstractAdminController extends AbstractPageController
{
    protected function clearImagePath(string $projectDir, string $savePath): string
    {
        $path = str_replace('__ROOT__', $projectDir, $savePath);
        return preg_replace(['/\/{2,}/', '/\/{1,}$/'], ['/', ''], $path);
    }

    protected function deleteImage(string $packetName, ImageInterface $entity): void
    {
        if (empty($entity->getImage())) {
            return;
        }

        $params = $this->get('parameter_bag')->get($packetName);
        $path = $this->clearImagePath(
            $params['projectDir'],
            $params['image']['save_path']
        );

        if (is_file($path.'/'.$entity->getImage())) {
            unlink($path.'/'.$entity->getImage());
        }

        foreach ($params['image'] as $key => $param) {
            if (strpos($key, '_size') !== false) {
                list($sizeType,) = explode('_', $key);
                if (is_file($path.'/'.$sizeType.'_'.$entity->getImage())) {
                    unlink($path.'/'.$sizeType.'_'.$entity->getImage());
                }
            }
        }

        $entity->setImage('');
    }

    protected function uploadImage(Form $form, string $packetName, ImageInterface $entity): void
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();

            if ($image) {
                $params = $this->get('parameter_bag')->get($packetName);
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                $path = $this->clearImagePath($params['projectDir'], $params['image']['save_path']);
                $safeFilename = $this->getSlugger()->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                $this->deleteImage($packetName, $entity);

                try {
                    $image->move(
                        $path,
                        $newFilename
                    );

                    $imagine = new Imagine();

                    foreach ($params['image'] as $key => $param) {
                        if (strpos($key, '_size') !== false) {
                            list($sizeType, ) = explode('_', $key);
                            list($width, $height) = explode("x", $param);
                            list($iwidth, $iheight) = getimagesize($path.'/'.$newFilename);
                            $ratio = $iwidth / $iheight;

                            if ($width / $height > $ratio) {
                                $width = $height * $ratio;
                            } else {
                                $height = $width / $ratio;
                            }

                            $photo = $imagine->open($path.'/'.$newFilename);
                            $photo->resize(new Box($width, $height))->save($path.'/'.$sizeType.'_'.$newFilename);
                        }
                    }

                    if (!empty($params['image']['is_delete_origin'])) {
                        unlink($path.'/'.$newFilename);
                    }

                } catch (FileException $exception) {
                    throw $exception;
                }


                $entity->setImage($newFilename);
            }
        }
    }

    protected function setMenuUrlPath(MenuInterface $menu, MenuRepository $menuRepository, string $separator = '/'): void
    {
        $urlByName = $this->transliterate($menu->getUrl(), $menu->getName());
        $menu->setUrl($urlByName);

        $items = $menuRepository
            ->getAllQueryBuilder($menu)
            ->andWhere("{$menuRepository->getAlias()}.url IS NOT NULL")
            ->getQuery()
            ->getResult();

        foreach ($items as $item) {

            $parents = $menuRepository
                ->getParentsByItemQueryBuilder($item)
                ->andWhere("{$menuRepository->getAlias()}.url IS NOT NULL")
                ->getQuery()
                ->getResult();
            ;
            if (!empty($parents)) {

                $path = '';
                foreach ($parents as $parent) {
                    $path .= $separator . $parent->getUrl();
                }
                $item->setPath($path);
            }
        }

    }

}
