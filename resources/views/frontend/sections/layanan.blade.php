  <!-- Layanan Section -->
  <section id="layanan" class="py-5 bg-white">
      <div class="container">
          <h2 class="text-center text-secondary-custom fw-semibold mb-5 fs-2">Layanan Bimas Islam</h2>

          <div class="row g-4 justify-content-center">
              @forelse($layanans as $layanan)
                  <div class="col-md-4">
                      <div class="card border-0 shadow-sm h-100 text-center p-4 position-relative">
                          <a href="{{ route('layanan.detail', $layanan->slug) }}"
                              class="stretched-link text-decoration-none"></a>
                          <div class="display-5 text-primary-custom mb-3">
                              {{-- @if (Str::startsWith($layanan->ikon, 'fa')) --}}
                              @php
                                  $ikonClean = trim($layanan->ikon);
                                  $isLocal = \Illuminate\Support\Facades\Storage::disk('public')->exists($ikonClean);
                                  $lottieSrc = $isLocal ? asset('storage/' . $ikonClean) : $ikonClean;
                              @endphp
                              @if (\Illuminate\Support\Str::endsWith($ikonClean, '.json'))
                                  <lottie-player src="{{ $lottieSrc }}" background="transparent" speed="1"
                                      style="width: 150px; height: 150px; margin: 0 auto;" loop
                                      autoplay></lottie-player>
                              @elseif (\Illuminate\Support\Str::startsWith($ikonClean, 'fa'))
                                  <i class="{{ $ikonClean }}"></i>
                              @else
                                  {{ $ikonClean }}
                              @endif
                          </div>
                          <h5 class="fw-semibold text-secondary-custom">{{ $layanan->judul }}</h5>
                          <p class="text-muted">{{ $layanan->deskripsi_singkat }}</p>
                      </div>
                  </div>
              @empty
                  <div class="col-12 text-center">
                      <p>Belum ada data layanan.</p>
                  </div>
              @endforelse
          </div>
      </div>
  </section>
