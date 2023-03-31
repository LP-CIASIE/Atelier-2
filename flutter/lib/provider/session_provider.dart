import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import '../class/user.dart';

class SessionProvider extends ChangeNotifier {
  User _user = User(accessToken: '', refreshToken: '');

  User get user => _user;

  set(User user) {
    _user = user;
  }

  Future<Map<String, dynamic>> signIn(String email, String password) async {
    Map bodyHttp = {
      'email': email,
      'password': password,
    };
    try {
      dynamic responseHttp = await http.post(
        Uri.parse('http://gateway.atelier.local:8000/signin'),
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8',
        },
        body: jsonEncode(bodyHttp),
      );
      Map<String, dynamic> response = jsonDecode(responseHttp.body);
      if (response.containsKey('code')) {
        return {
          "ok": false,
          "message": utf8.decode(response['message'].codeUnits),
        };
      } else {
        _user = User(
            accessToken: response['access-token'],
            refreshToken: response['refresh-token']);
        return {
          "ok": true,
        };
      }
    } catch (error) {
      return {
        "ok": false,
        "message":
            'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer',
      };
    }

    //     if (response.statusCode == 200) {
    //   Provider.of<SessionProvider>(context, listen: false)
    //       .setUser(response.body);
    //   setState(() {
    //     errorMessage = "Connexion réussie";
    //   });
    // } else {
    //   setState(() {
    //     _passwordController.text = '';
    //     errorMessage = "Un problème est survenu lors de la connexion";
    //   });
    //   // Erreur lors de l'envoi des données
    // }
  }
}
