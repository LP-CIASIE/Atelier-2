import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:lp1_ciasie_atelier_2/class/custom_exception.dart';

import 'package:lp1_ciasie_atelier_2/class/event.dart';
import 'package:lp1_ciasie_atelier_2/class/user.dart';

import 'package:http/http.dart' as http;
import 'package:lp1_ciasie_atelier_2/provider/session_provider.dart';
import 'package:lp1_ciasie_atelier_2/screen/auth/sign_in_screen.dart';
import 'package:provider/provider.dart';

class EventPage extends StatefulWidget {
  final String idEvent;

  const EventPage({
    Key? key,
    required this.idEvent,
  }) : super(key: key);

  @override
  State<EventPage> createState() => _EventPageState();
}

class _EventPageState extends State<EventPage> {
  @override
  void initState() {
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Home'),
      ),
      body: FutureBuilder<Event>(
        future: fetchEvent(context),
        builder: (context, snapshot) {
          if (snapshot.hasData) {
            return Center(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 12),
                    child: Text( 
                
                      snapshot.data!.title,
                        style: const TextStyle(fontSize: 24.6)),
                  ),

                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 12),
                    child: Text(
                    snapshot.data!.date.toString(),
                        style: const TextStyle(fontSize: 19.6)),
                  ),
                                    Padding(
                    padding: const EdgeInsets.symmetric(vertical: 12),
                    child: Text(snapshot.data!.description,
                       ),
                  ),
                ],
              ),
            );
          } else if (snapshot.hasError) {
            return Center(
              child: Text('Failed to load events: ${snapshot.error}'),
            );
          } else {
            return const Center(child: CircularProgressIndicator());
          }
        },
      ),
    );
  }

  Future<Event> fetchEvent(context) async {
    try {
      User user =
          Provider.of<SessionProvider>(context, listen: false).userDataSession;

      dynamic responseHttp = await http.get(
        Uri.parse('http://gateway.atelier.local:8000/events/${widget.idEvent}'),
        headers: <String, String>{
          'Authorization': 'Bearer ${user.accessToken}',
          'Content-Type': 'application/json; charset=UTF-8',
        },
      );
      if (user.accessToken == "") {
        Navigator.push(
          context,
          MaterialPageRoute(builder: (context) => const SignInPage()),
        );
      }
      if (!responseHttp.body.isEmpty) {
        Map<String, dynamic> response = jsonDecode(responseHttp.body);

        if (response.containsKey('error')) {
          if (response['error'] == 404) {
            throw CustomException(message: "Vos évènements sont introuvables");
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
          Map map = response['event'];

          Event event = Event.fromMap(map);

          return event;
        } else {
          throw CustomException(message: "Vous n'avez pas encore d'évènement.");
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
}
