<?php

namespace App\Tests\Form;

use App\Application\Review\DTO\CreateReviewDTO;
use App\Form\ReviewType;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class ReviewTypeTest extends KernelTestCase
{
    private FormFactoryInterface $factory;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->factory = static::getContainer()->get(FormFactoryInterface::class);
    }

    #[DataProvider('provideFormData')]
    public function testFormValidation(array $data, bool $valid): void
    {
        $form = $this->factory->create(ReviewType::class);

        $form->submit($data);

        $this->assertEquals($valid, $form->isValid());
    }

    public static function provideFormData(): array
    {
        return [
            'invalid name' => [[
                'companyName' => '',
                'rating' => 5,
                'reviewText' => 'test review text',
                'authorEmail' => 'email@example.com',
            ], false],
            'invalid rating' => [[
                'companyName' => 'test name',
                'rating' => 7,
                'reviewText' => 'test review text',
                'authorEmail' => 'email@example.com',
            ], false],
            'invalid email' => [[
                'companyName' => 'test name',
                'rating' => 5,
                'reviewText' => 'test review text',
                'authorEmail' => 'emailexample.com',
            ], false],
            'valid data' => [[
                'companyName' => 'test name',
                'rating' => 5,
                'reviewText' => 'test review text',
                'authorEmail' => 'email@example.com',
            ], true],
        ];
    }
}
