import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:lp1_ciasie_atelier_2/class/custom_exception.dart';
import 'package:lp1_ciasie_atelier_2/class/user.dart';
import 'package:lp1_ciasie_atelier_2/provider/session_provider.dart';
import 'package:lp1_ciasie_atelier_2/screen/error_screen.dart';
import 'package:lp1_ciasie_atelier_2/screen/event/event_edit_screen.dart';
import 'package:lp1_ciasie_atelier_2/screen/loading_screen.dart';
import 'package:provider/provider.dart';

class EventEditBuilderPage extends StatelessWidget {
  final String idEvent;
  const EventEditBuilderPage({super.key, required this.idEvent});

  Future<Map<String, dynamic>> event(context) async {
    try {
      User user =
          Provider.of<SessionProvider>(context, listen: false).userDataSession;
      dynamic responseHttp = await http.get(
        Uri.parse('http://gateway.atelier.local:8000/events/$idEvent'),
        headers: <String, String>{
          'Authorization': 'Bearer ${user.accessToken}',
          'Content-Type': 'application/json; charset=UTF-8',
        },
      );

      if (!responseHttp.body.isEmpty) {
        Map<String, dynamic> response = jsonDecode(responseHttp.body);

        if (response.containsKey('error')) {
          print('KO');
          if (response['error'] == 404) {
            throw CustomException(message: "Cet utilisateur n'existe pas.");
          }
          if (response['error'] == 401) {
            throw CustomException(
                message:
                    "Vous n'êtes pas autorisé à accéder à cette ressource.");
          }
          if (response.containsKey('message')) {
            throw CustomException(message: response['message']);
          }
          throw CustomException(
              message: "Une erreur est survenue : ${response['code']}.");
        } else if (response.containsKey('event')) {
          print('OK');
          print(response['event']);
          return response['event'];
        } else {
          print('KO?');
          throw CustomException(
              message:
                  "Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.");
        }
      } else {
        throw CustomException(
            message:
                "Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.");
      }
    } catch (error) {
      if (error is! CustomException) {
        throw CustomException(
            message:
                'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.');
      }
      rethrow;
    }
  }

  // This widget is the root of your application.
  @override
  Widget build(BuildContext context) {
    return Consumer<SessionProvider>(
      builder: (context, session, child) {
        return FutureBuilder(
          future: event(context),
          builder: (context, snapshot) {
            if (snapshot.hasError) {
              return ExceptionPage(error: snapshot.error);
            } else if (snapshot.hasData) {
              return EventEditPage(user: snapshot.data);
            } else {
              return const LoadingPage();
            }
          },
        );
      },
    );
  }
}
