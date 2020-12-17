<?php

function validate($field, $value){
    switch($field){
      case "fornavn":
        $regexp = "/^[a-zA-ZæøåÆØÅ\ \.\-]{2,50}$/";
        if(!preg_match($regexp, $value)){
          return "<br/>Navnet har feil format, skal v&aelig;re bokstaver eller '.', '-' eller ' '.";
        }
        break;
        case "etternavn":
        $regexp = "/^[a-zA-ZæøåÆØÅ\ \.\-]{2,50}$/";
        if(!preg_match($regexp, $value)){
          return "<br/>Etternavnet har feil format.";
        }
        break;
        case "adresse":
        $regexp = "/^[0-9a-zA-ZæøåÆØÅ\ \.\-]{2,50}$/";
        if(!preg_match($regexp, $value)){
          return "<br/>Adressen har feil format, skal validere bokstaver, tall og mellomrom.";
        }
        break;
        case "postnr":
        $regexp = "/^[0-9]{4}$/";
        if(!preg_match($regexp, $value)){
          return "<br/> Postnrummeret er av feil format";
        }
        break;
        case "poststed":
        $regexp = "/^[a-zA-ZæøåÆØÅ\ \.\-]{2,50}$/";
        if(!preg_match($regexp, $value)){
          return "<br/> poststedet har feil format.";
        }
        break;
        case "telefonnr":
        $regexp = "/^[0-9]{8}$/";
        if(!preg_match($regexp, $value)){
          return "<br/>telefonnummeret er ikke gyldig.";
        }
        break;
        case "nasjonalitet":
        $regexp = "/^[a-zA-ZæøåÆØÅ\ \.\-]{2,50}$/";
        if(!preg_match($regexp, $value)){
          return "<br/> Nasjonaliteten er ikke i riktig format.";
        }
        break;
        default:
        return "<br/>Feltet du prøver å validere finnes ikke;";
        break;
    }

  }
