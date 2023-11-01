<?php
/**
 * Copyright © Open Techiz All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magentiz\SplitDb\Console\Command;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Config\File\ConfigFilePool;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Command for change db mode
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ChangeDbMode extends Command
{
    const MODE = 'mode';

    /** Command name */
    const NAME = 'db:mode:set';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $options = [
            new InputOption(
                self::MODE,
                null,
                InputOption::VALUE_REQUIRED,
                'Name'
            )
        ];

        $this->setName(self::NAME)
            ->setDescription('Database mode set.')
            ->setDefinition($options);;

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($mode = $input->getOption(self::MODE)) {
            if ($mode == 'default' || $mode == 'split') {
                $isActive = ($mode == 'split');
                $write = ObjectManager::getInstance()->create(\Magento\Framework\App\DeploymentConfig\Writer::class);
                $write->saveConfig([
                    ConfigFilePool::APP_ENV => [
                        'db' => [
                            'connection' => [
                                'default' => [
                                    'is_split' => $isActive
                                ]
                            ]
                        ]
                    ]
                ]);
            } else {
                $output->writeln('Mode allow is: default or split');
            }
        } else {
            $output->writeln('Please input mode: default or split');
        }
    }
}
