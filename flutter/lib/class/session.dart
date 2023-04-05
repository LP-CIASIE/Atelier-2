class Session {
  final String id;
  String accessToken;
  final String refreshToken;

  Session({
    required this.id,
    required this.accessToken,
    required this.refreshToken,
  });
}
