<?php
/**
 * @date    2017-06-13
 * @file    ListPricesCommand.php
 * @author  Patrick Mac Gregor <macgregor.porta@gmail.com>
 */

namespace Macghriogair\Coin\Command;

use Macghriogair\Coin\Client;
use Macghriogair\Coin\Coin;
use Macghriogair\Coin\Fiat;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class ListPricesCommand extends Command
{
    protected function configure()
    {
        $this->setName('cb:prices')
            ->setDescription('List current prices.')
            ->addArgument(
                'currency',
                InputArgument::OPTIONAL,
                'The target currency, defaults to ' . Fiat::EUR
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $coins = [Coin::BTC, Coin::ETH, Coin::LTC];
        $client = new Client('', '');
        $fiat = $input->getArgument('currency') ?: Fiat::EUR;

        $table = new Table($output);
        $table->setHeaders(['Coin', 'Currency', 'Buy', 'Sell', 'Spot']);

        foreach ($coins as $coin) {
            $prices = $client->prices($coin, $fiat);
            $table->addRow([
                $coin,
                $fiat,
                $prices['buy']->getAmount(),
                $prices['sell']->getAmount(),
                $prices['spot']->getAmount(),
            ]);
        }

        $table->render();
    }
}
