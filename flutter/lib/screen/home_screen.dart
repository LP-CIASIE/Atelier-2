import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:lp1_ciasie_atelier_2/screen/profil/profil_builder_screen.dart';
import 'package:lp1_ciasie_atelier_2/screen/auth/sign_in_screen.dart';
import 'package:lp1_ciasie_atelier_2/screen/auth/sign_up_screen.dart';

class HomePage extends StatefulWidget {
  final List<Event> events;

  const HomePage({Key? key, required this.events}) : super(key: key);

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
//   User user = User(accessToken: ' ', refreshToken: '');
//   Provider.of<Session
// // Provider.of<SessionProvider>(context, listen: false).set()

  @override
  Widget build(BuildContext context) {
      
    
    return Scaffold(
      appBar: AppBar(
        title: const Text('Home'),
      ),
      body: Column(children: [
        ElevatedButton(
          onPressed: () {
            Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => const SignInPage()),
            );
          },
          child: const Text("Se connecter"),
        ),
        ElevatedButton(
          onPressed: () {
            Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => const SignUpPage()),
            );
          },
          child: const Text("S'inscrire"),
        ),
      ]),
    );
  }
}

Future<List<Event>> fetchEvents(context) async {

  try {
    User user =Provider.of<SessionProvider>(context, listen: false).userDataSession;

    dynamic responseHttp = await http.get(
      Uri.parse('http://gateway.atelier.local:8000/events?page=1&size=150'),
      headers: <String, String>{
        'Authorization': 'Bearer ${user.accessToken}',
        'Content-Type': 'application/json; charset=UTF-8',
      },
    );
    if(user.accessToken == ""){
      
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
              message: "Vous n'êtes pas autorisé à accéder à cette ressource.");
        }
        if (response.containsKey('message')) {
          throw CustomException(message: response['message']);
        }
        throw CustomException(
            message: "Une erreur est survenue : ${response['code']}.");
      } else if (response.containsKey('events')) {

        List list = response['events'];

        List<Event> events = [];


        for (var event in list) {
          events.add(Event(idEvent: event['id'], title: event['title'], description: event['description'], date: event['date'], isPublic: event['is_public'],));
        }

        return events;
      } else {
        throw CustomException(message: "Vous n'avez pas encore d'évènement.");
      }
    } else {
      throw CustomException(message: "Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.");
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
