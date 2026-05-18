<?php
// Завдання 1
echo " Завдання 1\n";
$arr = [];
for ($i = 0; $i < 5; $i++) {
    $arr[] = rand(1, 10);
}
$factorials = [];
foreach ($arr as $num) {
    $fact = 1;
    for ($j = 1; $j <= $num; $j++) {
        $fact *= $j;
    }
    $factorials[] = $fact;
}
echo "Масив: " . implode(", ", $arr) . "\n";
echo "Факторіали: " . implode(", ", $factorials) . "\n\n";
// Завдання 2
echo " Завдання 2\n";
$arr = [];
for ($i = 0; $i < 30; $i++) {
    $arr[] = rand(1, 100);
}

$sum = 0;
foreach ($arr as $num) {
    if ($num % 3 == 0 && $num % 5 == 0) {
        $sum += $num;
    }
}
echo "Сума: $sum\n\n";
// Завдання 3
echo " Завдання 3\n";
$inputString = readline("Введіть числа через пробіл: ");
$arr = explode(" ", $inputString);
$max = $arr[0];
foreach ($arr as $num) {
    if ((int)$num > (int)$max) {
        $max = $num;
    }
}
echo "Масив: " . implode(", ", $arr) . "\n";
echo "Найбільше значення: $max\n\n";
// Завдання 4
echo " Завдання 4\n";
$arr = [];
for ($i = 0; $i < 20; $i++) {
    $arr[] = rand(10, 100);
}
$primeCount = 0;
foreach ($arr as $num) {
    $isPrime = true;
    for ($j = 2; $j <= sqrt($num); $j++) {
        if ($num % $j == 0) {
            $isPrime = false;
            break;
        }
    }
    if ($isPrime && $num > 1) {
        $primeCount++;
    }
}
echo "Масив: " . implode(", ", $arr) . "\n";
echo "Кількість простих чисел: $primeCount\n\n";
// Завдання 5
echo " Завдання 5\n";
$arr = [];
for ($i = 0; $i < 20; $i++) {
    $arr[] = rand(0, 30);
}
foreach ($arr as $key => $value) {
    if ($key % 2 == 0) {
        $arr[$key] = 0;
    }
}
echo "Масив після заміни:\n";
print_r($arr);
echo "\n";
// Завдання 6
echo " Завдання 6\n";
$arr = [];
for ($i = 0; $i < 12; $i++) {
    $arr[] = rand(-20, 20);
}
$sum = 0;
foreach ($arr as $num) {
    if ($num % 3 == 0) {
        $sum += $num;
    }
}
echo "Масив: " . implode(", ", $arr) . "\n";
echo "Сума елементів, кратних 3: $sum\n\n";
// Завдання 7
echo " Завдання 7\n";
$input = readline("Введіть прізвище та ім'я: ");
$parts = explode(" ", $input);
if (count($parts) >= 2) {
    $surname = $parts[0];
    $initial = mb_substr($parts[1], 0, 1, 'UTF-8') . ".";
    echo "Стислий формат: $surname $initial\n\n";
} else {
    echo "Помилка: потрібно ввести і прізвище, і ім'я.\n\n";
}
// Завдання 8
echo " Завдання 8\n";
$input = readline("Введіть роки через пробіл: ");
$arr = explode(" ", $input);
$currentYear = 2026;
$closestYear = null;
$minDiff = PHP_INT_MAX;
foreach ($arr as $year) {
    $year = (int)$year;
    if (($year % 4 == 0 && $year % 100 != 0) || ($year % 400 == 0)) {
        $diff = abs($year - $currentYear);
        if ($diff < $minDiff) {
            $minDiff = $diff;
            $closestYear = $year;
        }
    }
}
if ($closestYear !== null) {
    echo "Найближчий високосний рік до $currentYear: $closestYear\n\n";
} else {
    echo "Серед введених років немає жодного високосного.\n\n";
}
// Завдання 9
echo " Завдання 9\n";
$arr = [];
for ($i = 0; $i < 10; $i++) {
    $arr[] = rand(1, 100);
}
echo "Масив до обміну: " . implode(", ", $arr) . "\n";
$minIndex = array_keys($arr, min($arr))[0];
$maxIndex = array_keys($arr, max($arr))[0];
$temp = $arr[$minIndex];
$arr[$minIndex] = $arr[$maxIndex];
$arr[$maxIndex] = $temp;
echo "Масив після обміну: " . implode(", ", $arr) . "\n\n";
// Завдання 10
echo " Завдання 10\n";
$n = 5;
$sum = 0;
$squares = [];
for ($i = 1; $i <= $n; $i++) {
    $square = $i * $i;
    $squares[] = $square;
    $sum += $square;
}
echo "Квадрати чисел від 1 до $n: " . implode(", ", $squares) . "\n";
echo "Сума квадратів цих чисел: $sum\n";
