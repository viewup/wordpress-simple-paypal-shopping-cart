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

// Number of cards in the shopping basket
function get_number_of_cards() {
  foreach($_SESSION['simpleCart'] as $item) {
      if (stripos($item['name'], 'Card') !== false) {
        //echo $item['name'];
        $number_of_cards += $item['quantity']; 
      }
  }
  return $number_of_cards;
}

// Is there a print in the shopping basket?
function print_in_basket() {
  foreach($_SESSION['simpleCart'] as $item) {
      if (stripos($item['name'], 'Print') !== false) {
        return true;
      }
  }
  return false;
}

function calculate_shipping() {
  $number_of_cards = 0; 
  $total_shipping_cost = 0;
  $number_of_cards = get_number_of_cards();

  // if there's a print in the basket, that results in free shipping for everything.
  if (print_in_basket()) {
    $total_shipping_cost = 0.00;
  } else {
    // There's no print in the basket so let's calculate those shipping costs.
    if ($number_of_cards <= 4) {
      $total_shipping_cost = '0.99';
    } else if ($number_of_cards >= 5 && $number_of_cards <= 19) {
      $total_shipping_cost = '1.29';
    } else if ($number_of_cards >= 20 && $number_of_cards <= 49) {
      $total_shipping_cost = '3.49';
    } else if ($number_of_cards >= 50 && $number_of_cards <= 99) {
      $total_shipping_cost = '4.99';
    } else if ($number_of_cards >= 100 && $number_of_cards <= 249) {
      $total_shipping_cost = '7.99';
    } else if ($number_of_cards >= 250 && $number_of_cards <= 499) {
      $total_shipping_cost = '9.99';
    } else if ($number_of_cards <= 500) {
      $total_shipping_cost = '9.99';
    }
  }
  return $total_shipping_cost;
}

function calculate_discount() {
  //print_r($_SESSION); 
  // Find the quantity of cards 
  $number_of_cards = 0; 
  $card_price = '2.50';

  $number_of_cards = get_number_of_cards(); 
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
    $card_price = '2.10';
  } else if ($number_of_cards >= 250 && $number_of_cards <= 499) {
    $card_price = '2.00';
  } else if ($number_of_cards >= 500 && $number_of_cards <= 999) {
    $card_price = '2.00';
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
