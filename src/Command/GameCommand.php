<?php

namespace App\Command;

use App\Game;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class GameCommand extends Command
{
    function configure(): void
    {
        $this->setName('game')
            ->setDescription('Bees in the Trap Game!');
    }

    function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Welcome to Bees in the Trap Game!</info>');
        $helper = $this->getHelper('question');
        $questionHelper = $this->buildQuestionHelper();

        $game = new Game();
        $game->startGame();

        $option = null;
        do {
            $option = $helper->ask($input, $output, $questionHelper);

            if ($option == "hit") {
                $this->playRound($game, $output);
            } else if ($option == "auto") {
                while ($game->isPlaying()) {
                    $this->playRound($game, $output);
                }
            }
        } while ($option != "exit" && $game->isPlaying());

        $output->writeln("");
        $output->writeln("SUMMARY");
        $output->writeln("=====================");
        if ($option == "exit") {
            $output->writeln("<info>Bye bye!</info>");
        } else if ($game->human->getHP() > 0) {
            $output->writeln("<info>You won!</info>");
            $output->writeln("Total hits: " . count($game->hits));
            $output->writeln("Remaining HP: " . $game->human->getHP());
            $output->writeln("Total stings: " . count($game->stings));
        } else {
            $output->writeln("<error>The hive won :(</error>");
            $output->writeln("Total hits: " . count($game->hits));
            $output->writeln("Queen Bee HP: " . $game->queenBee->getHP());
            $output->writeln("Remaining alive bees (including the qeen bee): " . count($game->getAliveBees()));
            $output->writeln("Total stings: " . count($game->stings));
        }

        return Command::SUCCESS;
    }

    private function playRound(Game $game, OutputInterface $output)
    {
        $hit = $game->hit();

        $firstHit = $hit[0]; // human -> bee
        $output->writeln("");
        $output->writeln("--------------------------------------");
        if ($firstHit->attackedPlayer == null) {
            $output->writeln("Miss! You just missed the hive, better luck next time!");
        } else {
            $output->writeln("Direct Hit! You took " . $firstHit->damage . " hit points from a " . $firstHit->attackedPlayer->getType() . ". HP: " . $firstHit->attackedPlayer->getHP());
        }
        $output->writeLn("");

        if (count($hit) == 2) {
            $secondHit = $hit[1]; // bee -> human
            if ($secondHit->attackerPlayer == null) {
                $output->writeln("Buzz! That was close! The Queen Bee just missed you!");
            } else {
                $output->writeln("Sting! You just got stun by a " . $secondHit->attackerPlayer->getType() . ". HP: " . $secondHit->attackedPlayer->getHP());
            }
        }

        $output->writeln("Your HP: " . $game->human->getHP());
        $output->writeln("Queen Bee HP: " . $game->queenBee->getHP());
        $output->writeLn("Remaining bees: " . count($game->getAliveBees()));
    }

    private function buildQuestionHelper(): ChoiceQuestion
    {
        $question = new ChoiceQuestion(
            'Please select your option (defaults to hit)',
            ['auto', 'hit', 'exit'],
            0
        );
        $question->setErrorMessage('Option %s is invalid.');

        return $question;
    }
}