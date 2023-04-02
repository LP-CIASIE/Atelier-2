import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:lp1_ciasie_atelier_2/class/custom_exception.dart';
import '../class/user.dart';

class SessionProvider extends ChangeNotifier {
  User _user = User(id: '', accessToken: '', refreshToken: '');

  User get userDataSession => _user;

  Future<Map<String, dynamic>> get user async {
    try {
      dynamic responseHttp = await http.get(
        Uri.parse('http://gateway.atelier.local:8000/users/${_user.id}'),
        headers: <String, String>{
          'Authorization': 'Bearer ${_user.accessToken}',
          'Content-Type': 'application/json; charset=UTF-8',
        },
      );
      Map<String, dynamic> response = jsonDecode(responseHttp.body);

      if (response.containsKey('error')) {
        if (response['error'] == 404) {
          throw CustomException(message: "Cet utilisateur n'existe pas.");
        }
        if (response['error'] == 401) {
          throw CustomException(
              message: "Vous n'êtes pas autorisé à accéder à cette ressource.");
        }
        if (response.containsKey('message')) {
          throw CustomException(message: response['message']);
        }
        throw CustomException(
            message: "Une erreur est survenue : ${response['code']}.");
      }

      Map<String, dynamic> user = response['user'];
      user['accessToken'] = _user.accessToken;
      user['refreshToken'] = _user.refreshToken;

      return user;
    } catch (error) {
      if (error is! CustomException) {
        throw CustomException(
            message:
                'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.');
      }
      rethrow;
    }
  }

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
            id: response['id_user'],
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
            'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.',
      };
    }
  }
}
