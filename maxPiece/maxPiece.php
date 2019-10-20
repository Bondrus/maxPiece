<?php

echo 'Программа расстановки максимального количества фигур на шахматной доске' . "\n";

interface typePiece
{
    const King = 1; // Король
    const Queen = 2; // Королева
    const Rook = 3; // Ладья
    const Bishop = 4; // Слон
    const Horse = 5; // Конь
    const Pawn = 6; // Пешка
}

class Piece
{
    public $type; // тип фигуры
    public $x; // координата горизонтали
    public $y; // коордната вертикали

    function __construct(int $type, int $x, int $y)
    {
        $this->type = $type;
        $this->x = $x;
        $this->y = $y;
    }

    public static function print(int $type): void
    {
        switch ($type) {
            case typePiece::King:
                {
                    echo 'K';
                    break;
                }
            case typePiece::Queen:
                {
                    echo 'Q';
                    break;
                }
            case typePiece::Rook:
                {
                    echo 'R';
                    break;
                }
            case typePiece::Bishop:
                {
                    echo 'B';
                    break;
                }
            case typePiece::Horse:
                {
                    echo 'H';
                    break;
                }
            case typePiece::Pawn:
                {
                    echo 'P';
                    break;
                }

        }
    }

}

class Pole
{
    const SIZE = 8;
    public $pole = [];

    private function clearPole(): void
    {
        foreach (range(1, self::SIZE) as $item1) {
            foreach (range(1, self::SIZE) as $item2) {
                $this->pole[$item1][$item2] = false;
            }
        }
    }

    function __construct()
    {
        $this->clearPole();
    }

    public function print(array $piece): void
    {
        foreach ($piece as $item) {
            $this->pole[$item->x][$item->y] = $item->type;
        }
        foreach (range(1, self::SIZE) as $itemX) {
            echo '|';
            foreach (range(1, self::SIZE) as $itemY) {
                if ($this->pole[$itemX][$itemY] === false) {
                    echo ' |';
                } elseif ($this->pole[$itemX][$itemY] === true) {
                    echo ' |';
                } else {
                    echo Piece::print($this->pole[$itemX][$itemY]) . '|';
                }
            }
            echo "\n";
        }
    }
    // проверяем поле на битость
    public function testAttack(int $x, int $y): bool
    {
        return $this->pole[$x][$y];
    }
    // заполняем битые поля
    public function fillPole(array $piece): void
    {
        $this->clearPole();
        // помечаем битые поля как true
        foreach ($piece as $item) {
            switch ($item->type) {
                case typePiece::King:
                    {
                        foreach (range(-1, 1) as $i) {
                            $this->pole[$item->x + $i][$item->y + $i] = true;
                            $this->pole[$item->x - $i][$item->y + $i] = true;
                            $this->pole[$item->x][$item->y + $i] = true;
                            $this->pole[$item->x + $i][$item->y] = true;
                        }
                        break;
                    }
                case typePiece::Queen:
                    {
                        foreach (range(-7, 7) as $i) {
                            $this->pole[$item->x + $i][$item->y + $i] = true;
                            $this->pole[$item->x - $i][$item->y + $i] = true;
                            $this->pole[$item->x][$item->y + $i] = true;
                            $this->pole[$item->x + $i][$item->y] = true;
                        }
                        break;
                    }
                case typePiece::Rook:
                    {
                        foreach (range(-7, 7) as $i) {
                            $this->pole[$item->x][$item->y + $i] = true;
                            $this->pole[$item->x + $i][$item->y] = true;
                        }
                        break;
                    }
                case typePiece::Bishop:
                    {
                        foreach (range(-7, 7) as $i) {
                            $this->pole[$item->x + $i][$item->y + $i] = true;
                            $this->pole[$item->x - $i][$item->y + $i] = true;
                        }
                        break;
                    }
                case typePiece::Horse:
                    {
                        $this->pole[$item->x - 2][$item->y - 1] = true;
                        $this->pole[$item->x - 1][$item->y - 2] = true;
                        $this->pole[$item->x + 1][$item->y - 2] = true;
                        $this->pole[$item->x + 2][$item->y - 1] = true;
                        $this->pole[$item->x - 2][$item->y + 1] = true;
                        $this->pole[$item->x - 1][$item->y + 2] = true;
                        $this->pole[$item->x + 1][$item->y + 2] = true;
                        $this->pole[$item->x + 2][$item->y + 1] = true;
                        break;
                    }
                case typePiece::Pawn:
                    {
                        $this->pole[$item->x + 1][$item->y + 1] = true;
                        $this->pole[$item->x - 1][$item->y + 1] = true;
                        break;
                    }
            }
            $this->pole[$item->x][$item->y] = true;
        }
    }
}

$p = [];// массив фигур
$pol = new Pole; // доска

$piece = typePiece::Queen;  // тип фигуры

$countPiece = 0; // кол-во фигур
$i = 0;
$max=6; // выводим все что больше данного кол-ва
while (true) {
    if (($i < Pole::SIZE * Pole::SIZE)&&(false === $pol->testAttack(intdiv($i, Pole::SIZE) + 1,$i % Pole::SIZE + 1))) {
        $p[$countPiece] = new Piece($piece, intdiv($i, Pole::SIZE) + 1, $i % Pole::SIZE + 1);
        $countPiece = $countPiece + 1;
        $pol->fillPole($p);
        if (count($p) > $max) {
            $max=count($p);
            $pol->print($p);
            echo 'Фигуры ('.$max.') друг друга не бьют!' . "\n";
        }
    };
    $i = $i + 1;
    if ($i >= Pole::SIZE * Pole::SIZE) {
        $countPiece = $countPiece - 1;
        $i = ($p[$countPiece]->x - 1) * Pole::SIZE + $p[$countPiece]->y;
        if (($countPiece == 0) && ($i >= Pole::SIZE * Pole::SIZE)) {
            break;
        }
        unset($p[$countPiece]);
        $pol->fillPole($p);
    }
}
