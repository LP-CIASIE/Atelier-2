import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:lp1_ciasie_atelier_2/class/custom_exception.dart';
import 'package:lp1_ciasie_atelier_2/class/event.dart';
import 'package:lp1_ciasie_atelier_2/screen/event/event_pending_screen.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'event/events_screen.dart';
import 'package:http/http.dart' as http;
import 'package:lp1_ciasie_atelier_2/class/location.dart';
import 'package:lp1_ciasie_atelier_2/class/session.dart';
import 'package:lp1_ciasie_atelier_2/provider/session_provider.dart';
import 'package:lp1_ciasie_atelier_2/screen/auth/sign_in_screen.dart';
import 'package:provider/provider.dart';

class HomePage extends StatefulWidget {
  const HomePage({Key? key}) : super(key: key);

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  int _selectedIndex = 0;
  final controller = PageController(initialPage: 0);
  Future<List<Event>> futureEvents = Future.value([]);
  late List<Event> events;
  late List<Location> location;

  @override
  void initState() {
    super.initState();
  }

  Future<List<Event>> fetchEvents(context) async {
    try {
      Session user =
          Provider.of<SessionProvider>(context, listen: false).userDataSession;
      dynamic responseHttp = await http.get(
        Uri.parse('${dotenv.env['API_URL']}/events?page=1&size=150'),
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
        } else if (response.containsKey('events')) {
          List list = response['events'];

          List<Event> events = [];

          for (var event in list) {
            events.add(Event.fromMap(event));
          }

          return events;
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

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: PageView(
          controller: controller,
          physics: const NeverScrollableScrollPhysics(),
          onPageChanged: (index) {
            setState(() {
              _selectedIndex = index;
            });
          },
          children: const [
            EventsPage(),
            EventPendingPage(),
          ],
        ),
        bottomNavigationBar: BottomNavigationBar(
          items: const <BottomNavigationBarItem>[
            BottomNavigationBarItem(
              icon: Icon(Icons.calendar_month_outlined),
              label: 'Évènements',
            ),
            BottomNavigationBarItem(
              icon: Icon(Icons.pending_actions_rounded),
              label: 'Invitations',
            ),
          ],
          currentIndex: _selectedIndex,
          selectedItemColor: Colors.blue,
          onTap: (index) {
            setState(() {
              _selectedIndex = index;
              controller.jumpToPage(index);
            });
          },
        ));
  }
}
