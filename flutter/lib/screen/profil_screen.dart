import 'package:flutter/material.dart';
import 'package:lp1_ciasie_atelier_2/provider/session_provider.dart';
import 'package:provider/provider.dart';

class ProfilPage extends StatelessWidget {
  const ProfilPage({super.key});

  // This widget is the root of your application.
  @override
  Widget build(BuildContext context) {
    return Consumer<SessionProvider>(
      builder: (context, session, child) {
        return FutureBuilder(
          future: session.user,
          builder: (context, snapshot) {
            return Scaffold(
              appBar: AppBar(
                // Here we take the value from the FruitMaster object that was created by
                // the App.build method, and use it to set our appbar title.
                title: const Text(''),
                actions: [
                  IconButton(
                      onPressed: () => null,
                      icon: const Icon(Icons.edit_outlined))
                ],
              ),
              body: snapshot.hasData
                  ? Column(children: const [
                      Text('data'),
                    ])
                  : snapshot.hasError
                      ? Column(children: [
                          Container(
                            alignment: Alignment.center,
                            child: Text('${snapshot.error}'),
                          ),
                        ])
                      : Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Container(
                              alignment: Alignment.center,
                              child: const CircularProgressIndicator(),
                            ),
                          ],
                        ),
            );
          },
        );
      },
    );
  }
}
