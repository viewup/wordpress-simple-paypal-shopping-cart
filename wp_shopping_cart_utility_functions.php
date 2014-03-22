<?php

function wpspc_get_total_cart_qty()
{
    $total_items = 0;
    if(!isset($_SESSION['simpleCart'])){
        return $total_items;
    }
    foreach ($_SESSION['simpleCart'] as $item){
        $total_items += $item['quantity'];
    }
    return $total_items;
}

function wpspc_get_total_cart_sub_total()
{
    $sub_total = 0;
    if(!isset($_SESSION['simpleCart'])){
        return $sub_total;
    }    
    foreach ($_SESSION['simpleCart'] as $item){
        $sub_total += $item['price'] * $item['quantity'];
    }
    return $sub_total;
}

// RS This calculates the discount. Bespoke for AaminahSnowdon.co.uk
function calculate_discount() {
  //print_r($_SESSION); 
  // Find the quantity of cards 
  $number_of_cards = 0; 
  $card_price = '2.50';

  foreach($_SESSION['simpleCart'] as $item) {
    if (stripos($item['name'], 'Card') !== false) {
      //echo $item['name'];
      $number_of_cards += $item['quantity']; 
    }
  }

  //echo 'number of cards: '.$number_of_cards;
  
  // Determine the discount applied for the cards based on quantity bought
  if ($number_of_cards <= 4) {
    $card_price = '2.50';
  } else if ($number_of_cards >= 5 && $number_of_cards <= 19) {
    $card_price = '2.40';
  } else if ($number_of_cards >= 20 && $number_of_cards <= 49) {
    $card_price = '2.30';
  } else if ($number_of_cards >= 50 && $number_of_cards <= 99) {
    $card_price = '2.20';
  } else if ($number_of_cards >= 100 && $number_of_cards <= 249) {
    $card_price = '2.30';
  } else if ($number_of_cards >= 250 && $number_of_cards <= 499) {
    $card_price = '2.30';
  } else if ($number_of_cards >= 500 && $number_of_cards <= 999) {
    $card_price = '2.30';
  } else {
    $card_price = '2.00';
  }
 
  // Go through all the cards and set the card value
  for($i = 0; $i < count($_SESSION['simpleCart']); ++$i) {
    if (stripos($_SESSION['simpleCart'][$i]['name'], 'Card') !== false) {
      $_SESSION['simpleCart'][$i]['price'] = $card_price;
      $_SESSION['simpleCart'][$i]['orig_price'] = $card_price;
    }
  }

  // calculate the difference and return the card discount.
  $card_total = '2.50' * $number_of_cards; 
  $card_total_with_discount = $card_price * $number_of_cards; 
  $discount = $card_total - $card_total_with_discount;
 
  // return and display in totals section.
  return sprintf('%0.2f',$discount);
}
